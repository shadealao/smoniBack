<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryService;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\Contract;
use App\Models\LearnerProfile;
use App\Models\User;
use App\Models\Subscription;
use App\Models\CodeAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;

class ServiceController extends Controller
{
    
    /**
     * List all services.
     * 
     * @unauthenticated
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'integer|nullable',
            'type' => [Rule::in(['manual', 'automatic']),'nullable'],
        ]);

        $type = $request->type ?? 'manual';
        $category_id = $request->category_id ?? 1;

        $services = Service::where([
            'category_service_id' => $category_id,
            'type' => $type,
            'status' => true,
            ])->with(['category','items'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services,
            'message' => 'Liste des services récupérée avec succès.',
        ], 200);
    }

    /**
     * List Category services.
     * 
     * @unauthenticated
     */
    public function listCategory()
    {
        $categories = CategoryService::all();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ], 200);
    }

    /** 
     * Make subsription 
     * 
    */
    public function makeSubscribe(Request $request)
    {
        $validated = $request->validate([
            'mode' => 'required',
            'transaction' => 'required',
            'service_id' => 'required|integer',
        ]);

        $service = Service::find($request->service_id);

        $start_date = Carbon::now();
        $end_date = Carbon::now()->addDays((int)$service->time);

        $type_service = 0;
        $gearbox = 0;
        $hour = $service->hour;
        if($service){
           
           if(
                $service->title!='Fabrication Permis' &&
                $service->title!='Extension contrat' &&
                $service->title!='Examen code' &&
                $service->title!='Pack code'
           ) {
                $type_service = "Conduite";
                $gearbox = $service->type;
           } else {
                if($service->title=='Pack code') $type_service = "Pack Code";
                else if($service->title=='Extension contrat') $type_service = "Extension contact";
                else if($service->title=='Fabrication Permis') $type_service = "Fabrication Permis";
                else if($service->title=='Examen code') $type_service = "Examen code";
                else $type_service = "Autres";
                $gearbox = "aucun";
                $hour = 0;

           }
        }

        $subscription = Subscription::create([
            'learner_id' => auth()->user()->id, 
            'service_id' => $service->id, 
            'start_date' => $start_date, 
            'end_date' => $end_date,
            'mode' => $request->mode,
            'status' => 'active',
            'type_service' => $type_service,
            'hour' => $hour,
            'gearbox' => $gearbox,
            'transaction_id' => $request->transaction,
            'amount' => $service->price,
        ]);

        $this->sendmailer( auth()->user()->id, 'Souscription à un service', 'Souscription à un service', 'Vous venez de souscrire au service '.$service->title, 'subscribe');

        return response()->json([
            'success' => true,
            'data' => $subscription,
        ], 200);
    }

    /** 
     * My List subsription 
     * 
    */
    public function mySubscribe(User $user){
        
        $subscriptions = Subscription::where('learner_id', $user->id)->with(['service.items','learner']) ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $subscriptions,
        ], 200);
    }

    /** 
     * Pack Code Exist
     * 
    */
    public function packCode(){
        
        $service = Service::where('title','Pack code')->first();

        $exist = Subscription::where([
            'learner_id' => auth()->user()->id,
            'service_id' => $service->id,
            ])->first();

        $link = CodeAccess::orderBy('created_at','desc')->limit(1)->get();

        return response()->json([
            'success' => true,
            'data' => $exist ? true : false,
            'link' => $exist ? $link : null,
        ], 200);
    }

    /**
     * List Contrat services.
     * 
     */
    public function listContrat()
    {
        $contrats = Contract::where('student_id',auth()->user()->id)->with(['subscription.service'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $contrats,
        ], 200);
    }

    /** 
     * My info subscribe
     * 
    */
    public function infoSubscribe(){

        $hours = Subscription::where('learner_id', auth()->user()->id)->sum('hour');

        return response()->json([
            'success' => true,
            'data' => $hours,
        ], 200);
    }



    /**
     * Generate Contrat.
     */
    public function contrat()
    { 
        $ids = Contract::pluck('subscription_id')->toArray();
        $subscriptions = Subscription::whereNotIn('id',$ids)->get();
        foreach ($subscriptions as $subscription) {
            DB::beginTransaction();
                $user = User::find($subscription->learner_id);
                $info = LearnerProfile::where('user_id',$user->id)->first();
                
                $service = Service::find($subscription->service_id);
                $categories = CategoryService::find($service->category_service_id);

                if($categories->name == "Location Véhicule"){
                    if (!file_exists(public_path('storage/pdf/location/'))) {
                        mkdir(public_path('storage/pdf/location/'), 0755, true);
                    }
                    $pdf = 'storage/pdf/location/'.$subscription->transaction_id.'.pdf';

                    PDF::loadView('pdf.location', compact('subscription','user','info','service'))
                    ->setPaper('a4', 'portrait')
                    ->setWarnings(false)
                    ->save(public_path($pdf));
                }else{   
                    if (!file_exists(public_path('storage/pdf/contrat1/'))) {
                        mkdir(public_path('storage/pdf/contrat1/'), 0755, true);
                    }
                    $pdf = 'storage/pdf/contrat1/'.$subscription->transaction_id.'.pdf';

                    PDF::loadView('pdf.contrat1', compact('subscription','user','info','service'))
                    ->setPaper('a4', 'portrait')
                    ->setWarnings(false)
                    ->save(public_path($pdf));
                }
                
                $this->sendmailer( $user->id, 'Contrat lié au Service', 'Contrat lié au Service', 'Vous avez recu votre contrat. Veuillez vous connecter afin de disposer de ce dernier', 'subscribe');
                
            DB::commit();
            

            $contrat = Contract::create([
                'student_id' => $user->id,
                'subscription_id' => $subscription->id,
                'file_original' => $pdf,
                'file_signed' => "By Super Admin",
                'tag' => 'initial',
                'date' => new \DateTime(),
            ]);
        }
        return true;
        
    }


    public function fetchSubscribe(){

        $today = Carbon::today();
        $fifteenDaysFromNow = $today->copy()->addDays(15);

        $expiringSubscriptions = Subscription::whereDate('end_date', $fifteenDaysFromNow)
            ->with('learner','service')
            ->get();
        foreach ($expiringSubscriptions as $subscribe) {
            $content = 'Votre abonnement '.$subscribe->service->title.' expirera dans environ 15 jours.';
            $this->sendmailer($subscribe->learner_id, 'Expiration prochaine de votre abonnement', "Expiration prochaine de votre abonnement", $content, 'subcribe');
        }

        $expiringSubscriptions = Subscription::whereDate('end_date','<', $today)
            ->update([
                "status" => "expired",
            ]);

    }
}