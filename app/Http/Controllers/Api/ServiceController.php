<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryService;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\Contract;
use App\Models\learnerProfile;
use App\Models\User;
use App\Models\Subscription;
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
            'type' => [Rule::in(['manual', 'automatique']),'nullable'],
        ]);

        $type = $request->type ?? 'manual';
        $category_id = $request->category_id ?? 1;

        $services = Service::where([
            'category_service_id' => $category_id,
            'type' => $type
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
        $end_date = $start_date->addDays((int)$service->time);

        $hour = 0;
        $type_service = 0;
        $gearbox = 0;

        if($service){
           $hour = $service->time;
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

        return response()->json([
            'success' => true,
            'data' => $exist ? true : false,
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

        $subscriptions = Subscription::where('learner_id', auth()->user()->id)->with(['service.items']) ->orderBy('created_at','desc')->first();

        return response()->json([
            'success' => true,
            'data' => $subscriptions,
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

            $user = User::find($subscription->learner_id);
            $info = learnerProfile::where('user_id',$user->id)->first();

            if (!file_exists(public_path('storage/pdf/contrat1/'))) {
                mkdir(public_path('storage/pdf/contrat1/'), 0755, true);
            }
            
            $service = Service::find($subscription->service_id);
            $categories = CategoryService::find($service->category_service_id);

            if($categories->name == "Location Véhicule"){
                $pdf = 'storage/pdf/location/'.$subscription->transaction_id.'.pdf';

                PDF::loadView('pdf.location', compact('subscription','user','info','service'))
                ->setPaper('a4', 'portrait')
                ->setWarnings(false)
                ->save(public_path($pdf));
            }else{   
                $pdf = 'storage/pdf/contrat1/'.$subscription->transaction_id.'.pdf';

                PDF::loadView('pdf.contrat1', compact('subscription','user','info','service'))
                ->setPaper('a4', 'portrait')
                ->setWarnings(false)
                ->save(public_path($pdf));
            }
            

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
}