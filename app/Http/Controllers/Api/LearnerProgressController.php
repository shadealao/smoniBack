<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningHistory;
use App\Models\LearnerProgres;
use App\Models\Badge;
use App\Models\TrainingModule;
use App\Models\ModuleStep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LearnerProgressController extends Controller
{
    /**
     * Automatically update learner progress based on completed appointment.
     */
    public function trackProgress(Request $request, LearningHistory $learningHistory)
    {
        $user = Auth::user();

        // Only instructors or admins can update progress
        if ($user->role !== 'instructor' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs ou administrateurs peuvent mettre à jour la progression.',
            ], 403);
        }

        // Ensure the instructor is associated with the learning history
        if ($user->role === 'instructor' && $learningHistory->monitor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à mettre à jour cette progression.',
            ], 403);
        }

        $validated = $request->validate([
            'module_id' => 'required|exists:training_modules,id',
            'current_step_id' => 'required|exists:module_steps,id,module_id,' . $request->module_id,
            'instructor_notes' => 'nullable|string|max:1000',
        ]);

        $module = TrainingModule::findOrFail($validated['module_id']);
        $step = ModuleStep::findOrFail($validated['current_step_id']);
        $learner = User::findOrFail($learningHistory->student_id);

        DB::transaction(function () use ($learningHistory, $module, $step, $learner, $validated) {
            $progress = LearnerProgres::firstOrCreate(
                [
                    'learner_id' => $learner->id,
                    'module_id' => $module->id,
                ],
                [
                    'status' => 'not_started',
                    'started_at' => null,
                    'completed_at' => null,
                ]
            );

            // Update status and timestamps
            if ($progress->status === 'not_started') {
                $progress->status = 'in_progress';
                $progress->started_at = now();
            }

            $progress->current_step_id = $step->id;
            if (isset($validated['instructor_notes'])) {
                $progress->instructor_notes = $validated['instructor_notes'];
            }

            // Check if this is the last step and mark as completed
            $lastStep = $module->steps()->orderBy('display_order', 'desc')->first();
            if ($lastStep && $lastStep->id === $step->id) {
                $progress->status = 'completed';
                $progress->completed_at = now();
            }

            $progress->save();

            // Update learning history status
            $learningHistory->status = true;
            $learningHistory->save();
        });

        return response()->json([
            'success' => true,
            'data' => LearnerProgres::where('learner_id', $learner->id)
                ->where('module_id', $module->id)
                ->with(['module', 'currentStep'])
                ->first(),
            'message' => 'Progression mise à jour avec succès.',
        ], 200);
    }

    /**
     * Award a badge to a learner for completing a module.
     */
    public function awardBadge(Request $request, LearnerProgres $learnerProgres)
    {
        $user = Auth::user();

        // Only instructors or admins can award badges
        if ($user->role !== 'instructor' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs ou administrateurs peuvent attribuer des badges.',
            ], 403);
        }

        // Ensure the learner progress is completed
        if ($learnerProgres->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Le module doit être complété pour attribuer un badge.',
            ], 422);
        }

        // Check if badge already exists
        if (Badge::where('learner_id', $learnerProgres->learner_id)
            ->where('module_id', $learnerProgres->module_id)
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Un badge a déjà été attribué pour ce module.',
            ], 422);
        }

        $validated = $request->validate([
            'certification_url' => 'nullable|url',
        ]);

        $badge = Badge::create([
            'learner_id' => $learnerProgres->learner_id,
            'module_id' => $learnerProgres->module_id,
            'awarded_at' => now(),
            'validation_instructor_id' => $user->id,
            'certification_url' => $validated['certification_url'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $badge->load(['module', 'validationInstructor']),
            'message' => 'Badge attribué avec succès.',
        ], 201);
    }

    /**
     * List all badges for a learner.
     */
    public function listBadges(Request $request, User $learner)
    {
        $user = Auth::user();

        // Only the learner, their instructors, or admins can view badges
        if ($user->id !== $learner->id && $user->role !== 'instructor' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à voir les badges de cet apprenant.',
            ], 403);
        }

        // If instructor, ensure they have taught the learner
        if ($user->role === 'instructor') {
            $hasTaught = LearningHistory::where('monitor_id', $user->id)
                ->where('student_id', $learner->id)
                ->exists();
            if (!$hasTaught) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                ], 403);
            }
        }

        $badges = Badge::where('learner_id', $learner->id)
            ->with(['module', 'validationInstructor'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $badges,
            'message' => 'Liste des badges récupérée avec succès.',
        ], 200);
    }

    /**
     * List learner progress for a specific learner.
     */
    public function listProgress(Request $request, User $learner)
    {
        $user = Auth::user();

        // Only the learner, their instructors, or admins can view progress
        if ($user->id !== $learner->id && $user->role !== 'instructor' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à voir la progression de cet apprenant.',
            ], 403);
        }

        // If instructor, ensure they have taught the learner
        if ($user->role === 'instructor') {
            $hasTaught = LearningHistory::where('monitor_id', $user->id)
                ->where('student_id', $learner->id)
                ->exists();
            if (!$hasTaught) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                ], 403);
            }
        }

        $progress = LearnerProgres::where('learner_id', $learner->id)
            ->with(['module', 'currentStep'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $progress,
            'message' => 'Progression de l\'apprenant récupérée avec succès.',
        ], 200);
    }
}