<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LearnerProfile;
use App\Models\InstructorProfile;
use App\Models\OtpCode;
use App\Notifications\SendOtpCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;


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
            'genre' => ['required', Rule::in(['homme', 'femme', 'autre'])],
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
            'genre' => $validated['genre'],
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
            'genre' => ['required', Rule::in(['homme', 'femme', 'autre'])],
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
            'genre' => $validated['genre'],
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

    /**
     * Send OTP code for password reset.
     */
    public function sendOtpCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Generate a 6-digit OTP code
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP code in database
        OtpCode::create([
            'user_id' => $user->id,
            'code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false,
        ]);

        // Send OTP code via email
        Notification::send($user, new SendOtpCode($otpCode));

        return response()->json([
            'success' => true,
            'message' => 'Code OTP envoyé à votre email.',
        ], 200);
    }

    /**
     * Verify OTP code.
     */
    public function verifyOtpCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $validated['email'])->first();

        $otp = OtpCode::where('user_id', $user->id)
                      ->where('code', $validated['code'])
                      ->where('is_used', false)
                      ->where('expires_at', '>=', Carbon::now())
                      ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Code OTP invalide ou expiré.',
            ], 400);
        }

        // Mark OTP as used
        $otp->update(['is_used' => true]);

        // Generate a temporary token for password reset
        // $resetToken = Str::random(60);
        // $user->update(['password_reset_token' => $resetToken]);

        return response()->json([
            'success' => true,
            // 'reset_token' => $resetToken,
            'message' => 'Code OTP vérifié avec succès.',
        ], 200);
    }

    /**
     * Update password after OTP verification.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            // 'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $validated['email'])
                    // ->where('password_reset_token', $validated['reset_token'])
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token de réinitialisation invalide.',
            ], 400);
        }

        // Update password and clear reset token
        $user->update([
            'password' => Hash::make($validated['password']),
            // 'password_reset_token' => null,
        ]);

        // Revoke all existing tokens (optional, for security)
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe mis à jour avec succès.',
        ], 200);
    }

    /**
     * Update profil Image.
     */
    public function updateImage(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'file',
        ]);

        if($request->photo){
            $photo = $request->photo;
            $photoPath = $photo->store('profil', 'public');
        }

        auth()->user()->update([
            'photo' => $request->photo ? $photoPath : null,
        ]);

        return response()->json([
            'success' => true,
            'data' => auth()->user(),
            'message' => 'Photo Bien changer.',
        ], 200);
    }

    /**
     * Drop profil Image.
     */
    public function dropImage(Request $request)
    {
        
        auth()->user()->update([
            'photo' => null,
        ]);

        return response()->json([
            'success' => true,
            'data' => auth()->user(),
            'message' => 'Photo Bien supprimer.',
        ], 200);
    }

}