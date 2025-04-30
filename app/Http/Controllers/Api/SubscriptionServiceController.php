<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryService;
use App\Models\SubCategoryService;
use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionServiceController extends Controller
{
    /**
     * Create a new service with category, sub-category, and items (admin only).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent créer des services.',
            ], 403);
        }

        $validated = $request->validate([
            'category_label' => 'required|string|max:255',
            'sub_category_label' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'items' => 'nullable|array',
            'items.*.label' => 'required|string|max:255',
            'items.*.status' => 'boolean',
        ]);

        $service = DB::transaction(function () use ($validated) {
            $category = CategoryService::firstOrCreate(
                ['label' => $validated['category_label']],
                ['label' => $validated['category_label']]
            );

            $subCategory = SubCategoryService::firstOrCreate(
                ['label' => $validated['sub_category_label']],
                ['label' => $validated['sub_category_label']]
            );

            $service = Service::create([
                'category_service_id' => $category->id,
                'sub_category_service_id' => $subCategory->id,
                'title' => $validated['title'],
                'price' => $validated['price'],
            ]);

            if (!empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    ServiceItem::create([
                        'service_id' => $service->id,
                        'label' => $item['label'],
                        'status' => $item['status'] ?? true,
                    ]);
                }
            }

            return $service;
        });

        return response()->json([
            'success' => true,
            'data' => $service->load(['category', 'subCategory', 'items']),
            'message' => 'Service créé avec succès.',
        ], 201);
    }

    /**
     * List all services with their categories, sub-categories, and items.
     */
    public function index()
    {
        $services = Service::with(['category', 'subCategory', 'items'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services,
            'message' => 'Liste des services récupérée avec succès.',
        ], 200);
    }

    /**
     * Update a service, its category, sub-category, and items (admin only).
     */
    public function update(Request $request, Service $service)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent modifier des services.',
            ], 403);
        }

        $validated = $request->validate([
            'category_label' => 'sometimes|string|max:255',
            'sub_category_label' => 'sometimes|string|max:255',
            'title' => 'sometimes|string|max:255',
            'price' => 'sometimes|integer|min:0',
            'items' => 'nullable|array',
            'items.*.id' => 'sometimes|exists:service_items,id',
            'items.*.label' => 'required|string|max:255',
            'items.*.status' => 'boolean',
        ]);

        $service = DB::transaction(function () use ($service, $validated) {
            if (isset($validated['category_label'])) {
                $category = CategoryService::firstOrCreate(
                    ['label' => $validated['category_label']],
                    ['label' => $validated['category_label']]
                );
                $service->category_service_id = $category->id;
            }

            if (isset($validated['sub_category_label'])) {
                $subCategory = SubCategoryService::firstOrCreate(
                    ['label' => $validated['sub_category_label']],
                    ['label' => $validated['sub_category_label']]
                );
                $service->sub_category_service_id = $subCategory->id;
            }

            $service->update(array_filter([
                'category_service_id' => $service->category_service_id,
                'sub_category_service_id' => $service->sub_category_service_id,
                'title' => $validated['title'] ?? $service->title,
                'price' => $validated['price'] ?? $service->price,
            ]));

            if (!empty($validated['items'])) {
                $existingItemIds = $service->items->pluck('id')->toArray();
                $providedItemIds = array_filter(array_column($validated['items'], 'id'));

                // Delete items not included in the update
                ServiceItem::where('service_id', $service->id)
                    ->whereNotIn('id', $providedItemIds)
                    ->delete();

                foreach ($validated['items'] as $itemData) {
                    $item = isset($itemData['id']) && in_array($itemData['id'], $existingItemIds)
                        ? ServiceItem::find($itemData['id'])
                        : new ServiceItem(['service_id' => $service->id]);

                    $item->fill([
                        'label' => $itemData['label'],
                        'status' => $itemData['status'] ?? true,
                    ])->save();
                }
            }

            return $service;
        });

        return response()->json([
            'success' => true,
            'data' => $service->load(['category', 'subCategory', 'items']),
            'message' => 'Service mis à jour avec succès.',
        ], 200);
    }

    /**
     * Delete a service (admin only).
     */
    public function destroy(Service $service)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent supprimer des services.',
            ], 403);
        }

        $service->delete(); // Cascades to delete items via onDelete('cascade')

        return response()->json([
            'success' => true,
            'message' => 'Service supprimé avec succès.',
        ], 200);
    }
}