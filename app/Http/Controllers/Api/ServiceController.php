<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryService;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\User;
use App\Models\Contract;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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

        $subscription = Subscription::create([
            'learner_id' => auth()->user()->id, 
            'service_id' => $service->id, 
            'start_date' => $start_date, 
            'end_date' => $end_date,
            'mode' => $request->mode,
            'status' => 'active',
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


}