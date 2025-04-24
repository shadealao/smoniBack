<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LearnerProfile;
use App\Models\InstructorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Update learner profile.
     */
    public function updateLearnerProfile(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->role !== 'learner') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas un apprenant.',
            ], 403);
        }

        $validated = $request->validate([
            'lastname' => 'sometimes|string|max:255',
            'firstname' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'birthdate' => 'sometimes|date|before:today',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'cin_number' => 'nullable|string|max:255',
            'cin_issue_date' => 'nullable|date',
            'cin_issue_place' => 'nullable|string|max:50',
            'permit_number' => 'nullable|string',
            'permit_issue_date' => 'nullable|date',
            'permit_category' => 'nullable|string',
        ]);

        // Update user fields
        $user->update(array_filter([
            'lastname' => $validated['lastname'] ?? $user->lastname,
            'firstname' => $validated['firstname'] ?? $user->firstname,
            'phone' => $validated['phone'] ?? $user->phone,
        ]));

        // Update or create learner profile
        $learnerProfile = $user->learnerProfile ?? new LearnerProfile(['user_id' => $user->id]);
        $learnerProfile->fill(array_filter([
            'birthdate' => $validated['birthdate'] ?? $learnerProfile->birthdate,
            'address' => $validated['address'] ?? $learnerProfile->address,
            'postal_code' => $validated['postal_code'] ?? $learnerProfile->postal_code,
            'cin_number' => $validated['cin_number'] ?? $learnerProfile->cin_number,
            'cin_issue_date' => $validated['cin_issue_date'] ?? $learnerProfile->cin_issue_date,
            'cin_issue_place' => $validated['cin_issue_place'] ?? $learnerProfile->cin_issue_place,
            'permit_number' => $validated['permit_number'] ?? $learnerProfile->permit_number,
            'permit_issue_date' => $validated['permit_issue_date'] ?? $learnerProfile->permit_issue_date,
            'permit_category' => $validated['permit_category'] ?? $learnerProfile->permit_category,
        ]))->save();

        return response()->json([
            'success' => true,
            'data' => $user->load('learnerProfile'),
            'message' => 'Profil apprenant mis à jour avec succès.',
        ], 200);
    }

    /**
     * Update instructor profile.
     */
    public function updateInstructorProfile(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas un moniteur.',
            ], 403);
        }

        $validated = $request->validate([
            'lastname' => 'sometimes|string|max:255',
            'firstname' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'specialty' => 'sometimes|string',
            'city' => 'sometimes|string',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'bio' => 'nullable|string',
            'certification_issue_date' => 'nullable|date',
            'certification_number' => 'nullable|numeric|min:0',
        ]);

        // Update user fields
        $user->update(array_filter([
            'lastname' => $validated['lastname'] ?? $user->lastname,
            'firstname' => $validated['firstname'] ?? $user->firstname,
            'phone' => $validated['phone'] ?? $user->phone,
        ]));

        // Update or create instructor profile
        $instructorProfile = $user->instructorProfile ?? new InstructorProfile(['user_id' => $user->id]);
        $instructorProfile->fill(array_filter([
            'specialty' => $validated['specialty'] ?? $instructorProfile->specialty,
            'city' => $validated['city'] ?? $instructorProfile->city,
            'address' => $validated['address'] ?? $instructorProfile->address,
            'postal_code' => $validated['postal_code'] ?? $instructorProfile->postal_code,
            'bio' => $validated['bio'] ?? $instructorProfile->bio,
            'certification_issue_date' => $validated['certification_issue_date'] ?? $instructorProfile->certification_issue_date,
            'certification_number' => $validated['certification_number'] ?? $instructorProfile->certification_number,
        ]))->save();

        return response()->json([
            'success' => true,
            'data' => $user->load('instructorProfile'),
            'message' => 'Profil moniteur mis à jour avec succès.',
        ], 200);
    }

    /**
     * View learner profile details.
     */
    public function viewLearnerProfile()
    {
        $user = User::find(Auth::id());

        if ($user->role !== 'learner') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas un apprenant.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $user->load('learnerProfile'),
            'message' => 'Détails du profil apprenant récupérés avec succès.',
        ], 200);
    }

    /**
     * View instructor profile details.
     */
    public function viewInstructorProfile()
    {
        $user = User::find(Auth::id());

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas un moniteur.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $user->load('instructorProfile'),
            'message' => 'Détails du profil moniteur récupérés avec succès.',
        ], 200);
    }

    /**
     * View admin profile details.
     */
    public function viewAdminProfile()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas un administrateur.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Détails du profil administrateur récupérés avec succès.',
        ], 200);
    }
}