<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        
        $categoryServices = CategoryService::all();

        return response()->json([
            'success' => true,
            'data' => $categoryServices ,
        ], 200);
    }
}
