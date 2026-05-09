<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingModule;
use App\Models\ModuleStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrainingModuleController extends Controller
{
    /**
     * Create a new training module with steps (admin only).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent créer des modules.',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_hours' => 'required|integer|min:1',
            'required_for_license' => 'boolean',
            'display_order' => 'integer|min:0',
            'file' => 'nullable|file|mimes:pdf|max:2048', // Optional PDF, max 2MB
            'is_active' => 'boolean',
            'steps' => 'nullable|array',
            'steps.*.name' => 'required|string|max:255',
            'steps.*.description' => 'nullable|string',
            'steps.*.duration_minutes' => 'required|integer|min:0',
            'steps.*.step_type' => 'nullable|string|in:theory,practice,assessment',
            'steps.*.display_order' => 'integer|min:0',
            'steps.*.required_for_completion' => 'boolean',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('modules', $fileName, 'public');
        }

        $module = TrainingModule::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'duration_hours' => $validated['duration_hours'],
            'required_for_license' => $validated['required_for_license'] ?? false,
            'display_order' => $validated['display_order'] ?? 0,
            'file' => $filePath,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if (!empty($validated['steps'])) {
            foreach ($validated['steps'] as $step) {
                ModuleStep::create([
                    'module_id' => $module->id,
                    'name' => $step['name'],
                    'description' => $step['description'],
                    'duration_minutes' => $step['duration_minutes'],
                    'step_type' => $step['step_type'],
                    'display_order' => $step['display_order'] ?? 0,
                    'required_for_completion' => $step['required_for_completion'] ?? false,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $module->load('steps'),
            'message' => 'Module créé avec succès.',
        ], 201);
    }

    /**
     * List all training modules with their steps.
     */
    public function index()
    {
        $modules = TrainingModule::with('steps')->orderBy('display_order')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $modules,
            'message' => 'Liste des modules récupérée avec succès.',
        ], 200);
    }

    /**
     * Update a training module and its steps (admin only).
     */
    public function update(Request $request, TrainingModule $trainingModule)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent modifier des modules.',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'duration_hours' => 'sometimes|integer|min:1',
            'required_for_license' => 'sometimes|boolean',
            'display_order' => 'sometimes|integer|min:0',
            'file' => 'nullable|file|mimes:pdf|max:2048',
            'is_active' => 'sometimes|boolean',
            'steps' => 'nullable|array',
            'steps.*.id' => 'sometimes|exists:module_steps,id',
            'steps.*.name' => 'required|string|max:255',
            'steps.*.description' => 'nullable|string',
            'steps.*.duration_minutes' => 'required|integer|min:0',
            'steps.*.step_type' => 'nullable|string|in:theory,practice,assessment',
            'steps.*.display_order' => 'integer|min:0',
            'steps.*.required_for_completion' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($trainingModule->file && Storage::disk('public')->exists($trainingModule->file)) {
                Storage::disk('public')->delete($trainingModule->file);
            }
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['file'] = $file->storeAs('modules', $fileName, 'public');
        }

        $trainingModule->update(array_filter([
            'name' => $validated['name'] ?? $trainingModule->name,
            'description' => $validated['description'] ?? $trainingModule->description,
            'duration_hours' => $validated['duration_hours'] ?? $trainingModule->duration_hours,
            'required_for_license' => $validated['required_for_license'] ?? $trainingModule->required_for_license,
            'display_order' => $validated['display_order'] ?? $trainingModule->display_order,
            'file' => $validated['file'] ?? $trainingModule->file,
            'is_active' => $validated['is_active'] ?? $trainingModule->is_active,
        ]));

        if (!empty($validated['steps'])) {
            $existingStepIds = $trainingModule->steps->pluck('id')->toArray();
            $providedStepIds = array_filter(array_column($validated['steps'], 'id'));

            // Delete steps not included in the update
            ModuleStep::where('module_id', $trainingModule->id)
                ->whereNotIn('id', $providedStepIds)
                ->delete();

            foreach ($validated['steps'] as $stepData) {
                $step = isset($stepData['id']) && in_array($stepData['id'], $existingStepIds)
                    ? ModuleStep::find($stepData['id'])
                    : new ModuleStep(['module_id' => $trainingModule->id]);

                $step->fill([
                    'name' => $stepData['name'],
                    'description' => $stepData['description'] ?? null,
                    'duration_minutes' => $stepData['duration_minutes'],
                    'step_type' => $stepData['step_type'] ?? null,
                    'display_order' => $stepData['display_order'] ?? 0,
                    'required_for_completion' => $stepData['required_for_completion'] ?? false,
                ])->save();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $trainingModule->load('steps'),
            'message' => 'Module mis à jour avec succès.',
        ], 200);
    }

    /**
     * Delete a training module (admin only).
     */
    public function destroy(TrainingModule $trainingModule)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent supprimer des modules.',
            ], 403);
        }

        // Delete associated file
        if ($trainingModule->file && Storage::disk('public')->exists($trainingModule->file)) {
            Storage::disk('public')->delete($trainingModule->file);
        }

        $trainingModule->delete(); // Cascades to delete steps via onDelete('cascade')

        return response()->json([
            'success' => true,
            'message' => 'Module supprimé avec succès.',
        ], 200);
    }
}