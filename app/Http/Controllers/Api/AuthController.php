<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InstructorProfile;
use App\Models\LearnerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Ajout de la confirmation du mot de passe
            'phone' => 'required|string|max:20',
            'role' => ['required', Rule::in(['learner', 'instructor', 'admin'])],

            // Champs spécifiques pour learner
            'birthdate' => 'required_if:role,learner|date|before:today',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'cin_number' => 'nullable|string|max:255',
            'cin_issue_date' => 'nullable|date',
            'cin_issue_place' => 'nullable|string|max:50',
            'permit_number' => 'nullable|string',
            'permit_issue_date' => 'nullable|date',
            'permit_category' => 'nullable|string',

            // Champs spécifiques pour instructor
            'specialty' => 'required_if:role,instructor|string',
            'city' => 'required_if:role,instructor|string',
            'bio' => 'nullable|string',
            'certification_issue_date' => 'nullable|date',
            'certification_number' => 'nullable|numeric|min:0',
        ]);

        // Hachage du mot de passe
        $validated['password'] = Hash::make($validated['password']);

        // Création de l'utilisateur
        $user = User::create([
            'lastname' => $validated['lastname'],
            'firstname' => $validated['firstname'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
        ]);

        // Création du profil spécifique selon le rôle
        if ($validated['role'] === 'learner') {
            LearnerProfile::create([
                'user_id' => $user->id,
                'birthdate' => $validated['birthdate'],
                'address' => $validated['address'],
                'postal_code' => $validated['postal_code'] ?? null,
                'cin_number' => $validated['cin_number'] ?? null,
                'cin_issue_date' => $validated['cin_issue_date'] ?? null,
                'cin_issue_place' => $validated['cin_issue_place'] ?? null,
                'permit_number' => $validated['permit_number'] ?? null,
                'permit_issue_date' => $validated['permit_issue_date'] ?? null,
                'permit_category' => $validated['permit_category'] ?? null,

            ]);
        } elseif ($validated['role'] === 'instructor') {
            InstructorProfile::create([
                'user_id' => $user->id,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'bio' => $validated['bio'] ?? null,
                'certification_issue_date' => $validated['certification_issue_date'],
                'specialty' => $validated['specialty'] ?? null,
                'certification_number' => $validated['certification_number'] ?? null,
            ]);
        }
        // Pour le rôle 'admin', aucun profil supplémentaire n'est créé (ou ajoutez un modèle AdminProfile si nécessaire)

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'data' => $user->load($validated['role'] === 'learner' ? 'learnerProfile' : ($validated['role'] === 'instructor' ? 'instructorProfile' : [])), // Charger les relations si nécessaire
            'message' => 'Utilisateur inscrit avec succès'
        ], 201);
    }

    public function login(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Vérifier l'utilisateur
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects',
            ], 401);
        }

        // Générer un jeton
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'data' => $user,
            'message' => 'Connexion réussie',
        ], 200);
    }

    public function logout()
    {
        // Auth::logout();
        $user = Auth::logout();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur déconnecté'
        ]);
    }
}
