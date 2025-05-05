<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamRegistration;
use App\Models\Note;
use App\Models\User;
use App\Models\TrainingModule;
use App\Models\ModuleStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamNoteController extends Controller
{
    /**
     * Register a learner for an exam (instructor or admin only).
     */
    public function registerExam(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs ou administrateurs peuvent enregistrer un examen.',
            ], 403);
        }

        $validated = $request->validate([
            'learner_id' => 'required|exists:users,id',
            'registration_date' => 'required|date|after_or_equal:today',
        ]);

        // Ensure learner has role 'learner'
        $learner = User::findOrFail($validated['learner_id']);
        if ($learner->role !== 'learner') {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur spécifié n\'est pas un apprenant.',
            ], 422);
        }

        // If instructor, ensure they have taught the learner
        if ($user->role === 'instructor') {
            $hasTaught = \App\Models\LearningHistory::where('monitor_id', $user->id)
                ->where('student_id', $learner->id)
                ->exists();
            if (!$hasTaught) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                ], 403);
            }
        }

        $examRegistration = ExamRegistration::create([
            'learner_id' => $validated['learner_id'],
            'monitor_id' => $user->role === 'instructor' ? $user->id : \App\Models\User::where('role', 'instructor')->first()->id,
            'registration_date' => $validated['registration_date'],
            'status' => 'registered',
            'result_score' => null,
            'instructor_comments' => null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $examRegistration->load(['learner', 'monitor']),
            'message' => 'Inscription à l\'examen créée avec succès.',
        ], 201);
    }

    /**
     * Update exam result (instructor or admin only).
     */
    public function updateExamResult(Request $request, ExamRegistration $examRegistration)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs ou administrateurs peuvent mettre à jour les résultats d\'examen.',
            ], 403);
        }

        // If instructor, ensure they are the assigned monitor
        if ($user->role === 'instructor' && $examRegistration->monitor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à mettre à jour cet examen.',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:passed,failed,absent',
            'result_score' => 'required_if:status,passed,failed|nullable|numeric|min:0|max:100',
            'instructor_comments' => 'nullable|string|max:1000',
        ]);

        $examRegistration->update([
            'status' => $validated['status'],
            'result_score' => $validated['status'] === 'absent' ? null : $validated['result_score'],
            'instructor_comments' => $validated['instructor_comments'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $examRegistration->refresh()->load(['learner', 'monitor']),
            'message' => 'Résultat de l\'examen mis à jour avec succès.',
        ], 200);
    }

    /**
     * Create a note for a learner (instructor or admin only).
     */
    public function createNote(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs ou administrateurs peuvent créer des notes.',
            ], 403);
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'module_id' => 'nullable|exists:training_modules,id',
            'module_step_id' => 'nullable|exists:module_steps,id',
            'comment' => 'required|string|max:1000',
            'date' => 'required|date|before_or_equal:today',
        ]);

        // Ensure student has role 'learner'
        $student = User::findOrFail($validated['student_id']);
        if ($student->role !== 'learner') {
            return response()->json([
                'success' => false,
                'message' => 'L\'utilisateur spécifié n\'est pas un apprenant.',
            ], 422);
        }

        // If instructor, ensure they have taught the learner
        if ($user->role === 'instructor') {
            $hasTaught = \App\Models\LearningHistory::where('monitor_id', $user->id)
                ->where('student_id', $student->id)
                ->exists();
            if (!$hasTaught) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                ], 403);
            }
        }

        // If module_step_id is provided, ensure it belongs to the module
        if ($validated['module_step_id'] && $validated['module_id']) {
            $step = ModuleStep::findOrFail($validated['module_step_id']);
            if ($step->module_id !== $validated['module_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'L\'étape du module ne correspond pas au module spécifié.',
                ], 422);
            }
        }

        $note = Note::create([
            'monitor_id' => $user->id,
            'student_id' => $validated['student_id'],
            'module_id' => $validated['module_id'],
            'module_step_id' => $validated['module_step_id'],
            'comment' => $validated['comment'],
            'date' => $validated['date'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $note->load(['monitor', 'student', 'module', 'moduleStep']),
            'message' => 'Note créée avec succès.',
        ], 201);
    }

    /**
     * List exam registrations for a learner.
     */
    public function listExamRegistrations(Request $request, User $learner)
    {
        $user = Auth::user();

        // Only the learner, their instructors, or admins can view exam registrations
        if ($user->id !== $learner->id && $user->role !== 'instructor' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à voir les inscriptions aux examens de cet apprenant.',
            ], 403);
        }

        // If instructor, ensure they have taught the learner
        if ($user->role === 'instructor') {
            $hasTaught = \App\Models\LearningHistory::where('monitor_id', $user->id)
                ->where('student_id', $learner->id)
                ->exists();
            if (!$hasTaught) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                ], 403);
            }
        }

        $examRegistrations = ExamRegistration::where('learner_id', $learner->id)
            ->with(['learner', 'monitor'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $examRegistrations,
            'message' => 'Liste des inscriptions aux examens récupérée avec succès.',
        ], 200);
    }

    /**
     * List notes for a learner.
     */
    public function listNotes(Request $request, User $student)
    {
        $user = Auth::user();

        // Only the learner, their instructors, or admins can view notes
        if ($user->id !== $student->id && $user->role !== 'instructor' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à voir les notes de cet apprenant.',
            ], 403);
        }

        // If instructor, ensure they have taught the learner
        if ($user->role === 'instructor') {
            $hasTaught = \App\Models\LearningHistory::where('monitor_id', $user->id)
                ->where('student_id', $student->id)
                ->exists();
            if (!$hasTaught) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                ], 403);
            }
        }

        $notes = Note::where('student_id', $student->id)
            ->with(['monitor', 'student', 'module', 'moduleStep'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notes,
            'message' => 'Liste des notes récupérée avec succès.',
        ], 200);
    }
}