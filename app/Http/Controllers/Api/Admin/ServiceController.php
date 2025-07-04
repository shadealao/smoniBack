<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryService;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\Subscription;
use App\Models\CodeAccess;
use App\Models\User;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * @tags Zone Service (Admin)
 */
class ServiceController extends Controller
{
    /**
     * List Category Service
     */
    public function listCategoryService(Request $request){
        
        $categoryServices = CategoryService::all();

        return response()->json([
            'success' => true,
            'data' => $categoryServices ,
        ], 200);
    }
    
    /**
     * Add Category Service
     */
    public function addCategoryService(Request $request){

        $validated = $request->validate([
            'label' => 'required|unique:category_services,label',
        ]);

        $categoryService = CategoryService::create([
            'label' => $request->label,
        ]);

        return response()->json([
            'success' => true,
            'data' => $categoryService ,
        ], 200);
    
    }

    /**
     * Update Category Service
     */
    public function updateCategoryService(Request $request, CategoryService $categoryService){
        
        $validated = $request->validate([
            'label' => 'required|unique:category_services,label',
        ]);

        $categoryService->update([
            'label' => $request->label,
        ]);

        return response()->json([
            'success' => true,
            'data' => $categoryService ,
        ], 200);
    }

    /**
     * Delete Category Service
     */
    public function deleteCategoryService(Request $request, CategoryService $categoryService){

        $exist = Service::where('category_service_id',$categoryService->id)->first();

        if($exist){
            return response()->json([
                'success' => false,
                'message' => 'Il existe déjà des services pour cette catégorie' ,
            ], 403);
        }

        $categoryService->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catégorie bien supprimée' ,
        ], 200);
    
    }

    /**
     * List Service
     */
    public function listService(Request $request){
        
        $services = Service::with('items')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $services ,
        ], 200);
    }

    /**
     * List Service By Category
     */
    public function listServiceByCategory(Request $request, CategoryService $categoryService){
        
        $services = Service::where('category_service_id',$categoryService->id)->with('items')->get();

        return response()->json([
            'success' => true,
            'data' => $services ,
        ], 200);
    }

    /**
     * Add Service
     */
    public function addService(Request $request){

        $validated = $request->validate([
            'category_service_id' => 'required|exist:category_services,id',
            'title' => 'required',
            'price' => 'required|integer',
            'type' => [Rule::in(['automatique', 'manual']),'required'],
            'time' => 'required|integer',
            'hour' => 'required|integer',
            'items'=> 'array|nullable',
            'items.*.label' => '',
            'items.*.status' => 'boolean'
        ]);

        DB::beginTransaction();

            $service = Service::create([
                'category_service_id' => $request->category_service_id,
                'title' => $request->title,
                'price' => $request->price,
                'type' => $request->type,
                'time' => $request->time,
                'hour' => $request->hour,
            ]);

            foreach ($request->items as $item) {
                $serviceItem = ServiceItem::create([
                    'service_id' => $service->id,
                    'status' => $item['status'],
                    'label' => $item['label'],
                ]);
            }

        DB::commit();


        return response()->json([
            'success' => true,
            'message' => 'Service ajouté' ,
        ], 200);
    
    }

    /**
     * Update Service
     */
    public function updateService(Request $request, Service $service){

        $validated = $request->validate([
            'category_service_id' => 'required|exists:category_services,id',
            'title' => 'required',
            'price' => 'required|integer',
            'type' => [Rule::in(['automatique', 'manual']),'required'],
            'time' => 'required|integer',
            'hour' => 'required|integer',
        ]);


        $service->update([
            'category_service_id' => $request->category_service_id,
            'title' => $request->title,
            'price' => $request->price,
            'type' => $request->type,
            'time' => $request->time,
            'hour' => $request->hour,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Service ajouté' ,
        ], 200);
    
    }

    /**
     * List Service By Category
     */
    public function listServiceItem(Request $request, Service $service){
        
        $items = ServiceItem::where('service_id',$service->id)->get();

        return response()->json([
            'success' => true,
            'data' => $items ,
        ], 200);
    }

    /**
     * Add Service Item
     */
    public function addServiceItem(Request $request, Service $service){

        $validated = $request->validate([
            'items'=> 'required|array',
            'items.*.label' => 'required',
            'items.*.status' => 'boolean'
        ]);

        foreach ($request->items as $item) {
            $serviceItem = ServiceItem::create([
                'service_id' => $service->id,
                'status' => $item['status'],
                'label' => $item['label'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Service Item ajouté' ,
        ], 200);
    
    }

    /**
     * Update Service Item
     */
    public function updateServiceItem(Request $request, ServiceItem $serviceItem){

        $validated = $request->validate([
            'label' => 'required',
            'status' => 'boolean'
        ]);

            $serviceItem->update([
                'status' => $request->status,
                'label' => $request->label,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Service Item modifié' ,
        ], 200);
    
    }

    /**
     * Update Service Item
     */
    public function deleteServiceItem(Request $request, ServiceItem $serviceItem){

            $serviceItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service Item supprimé' ,
        ], 200);
    
    }

     /**
     * List Code to Link.
     * 
     */
    public function ListAccessCode(Request $request)
    {
        $access = CodeAccess::get();

        return response()->json([
            'success' => true,
            'data' => $access,
            'message' => "Code Access"
        ], 200);
    }

    /**
     * Ajout Code Access Link.
     * 
     */
    public function AddAccessCode(Request $request)
    {
        $validated = $request->validate([
            'liens' => 'string|required',
        ]);

        $codeaccess = CodeAccess::create([
            'liens' => $validated['liens']
        ]);

        return response()->json([
            'success' => true,
            'data' => $codeaccess,
            'message' => "Créer avec succès"
        ], 200);
    }

    
    /**
     * Supprimer Code Access Link.
     * 
     */
    public function DeleteAccessCode(Request $request, $codeacess)
    {
        $find = CodeAccess::find($codeacess);

        if($find) {
            $find->delete();

            return response()->json([
                'success' => true,
                'message' => "Supprimer avec succès",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Inexistant",
            ], 404);
        } 
    }

    /**
     * Liste des utilisateurs ayant un abonnement de conduite
     * 
     */
    public function LearnCodeAccess(Request $request)
    {
        $learners = Subscription::where('status','active')->where('type_service','Pack code')->with(['service.items','learner'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $learners,
            'message' => "Liste des apprennat avec un abonnement de code",
        ], 200);
        
    }

}
