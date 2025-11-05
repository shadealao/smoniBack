<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InstructorProfile;
use App\Models\LearnerProfile;
use App\Models\MeetingPoint;
use App\Models\User;
use App\Models\Vehicle;
use Exception;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Register
     */
    public function register(Request $request)
    {
        // Validation des données
        // 'birthdate' => 'required_if:role,learner|date|before:today',

        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'photo' => 'nullable|file',
            'genre' => ['required', Rule::in(['homme', 'femme', 'autre'])],
            'role' => ['required', Rule::in(['learner', 'instructor', 'admin'])],

            // Champs spécifiques pour learner
            'birthdate' => 'required|date|before:today',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'cin_number' => 'nullable|string|max:255',
            'cin_issue_date' => 'nullable|date',
            'cin_issue_place' => 'nullable|string|max:50',
            'permit_number' => 'nullable|string',
            'permit_issue_date' => 'nullable|date',
            'permit_category' => 'nullable|string',

            // Champs spécifiques pour instructor
            'tva' => 'nullable|boolean',
            'specialty' => 'nullable|string',
            'city' => 'nullable|string',
            'bio' => 'nullable|string',
            'certification_issue_date' => 'nullable|date',
            'certification_number' => 'nullable|numeric|min:0',

            'workZones' => 'sometimes|array',

            'workZones.*.label' => 'required_if:role,instructor|string|max:255',
            'workZones.*.address' => 'nullable|string|max:255',
            'workZones.*.city' => 'nullable|string|max:100',
            'workZones.*.postal_code' => 'nullable|string|max:20',
            'workZones.*.latitude' => 'nullable|numeric|between:-90,90',
            'workZones.*.longitude' => 'nullable|numeric|between:-180,180',

            'vehicles' => 'sometimes|array',
            
            'vehicles.*.brand' => 'required_if:role,instructor|string|max:100',
            'vehicles.*.model' => 'required_if:role,instructor|string|max:100',
            'vehicles.*.year' => 'required_if:role,instructor|integer|min:1900|max:' . date('Y'),
            'vehicles.*.plate_number' => 'required_if:role,instructor|string|max:20|unique:vehicles,plate_number',
            'vehicles.*.fuel_type' => 'required_if:role,instructor|in:essence,diesel,électrique,hybride',
            'vehicles.*.insurance_expiry' => 'nullable|date|after:today',
            'vehicles.*.technical_inspection_date' => 'nullable|date|after:today',
            'vehicles.*.photo_url' => 'nullable|file',
            'vehicles.*.color' => 'nullable|string|max:50',
            'vehicles.*.gearbox_type' => 'required_if:role,instructor|in:manual,automatic',
            'vehicles.*.status' => 'sometimes|in:available,maintenance,out_of_service',
        ]);

        // Hachage du mot de passe
        $validated['password'] = Hash::make($validated['password']);
        DB::beginTransaction();
        try{
            // Création de l'utilisateur
            if($request->photo){
                $photo = $request->photo;
                $photoPath = $photo->store('profil', 'public');
            }

            $user = User::create([
                'lastname' => $validated['lastname'],
                'firstname' => $validated['firstname'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'phone' => $validated['phone'],
                'genre' => $validated['genre'],
                'role' => $validated['role'],
                'is_active' => $validated['role'] == "instructor" ? false : true,
                'photo' => $request->photo ? $photoPath : null,
            ]);

            // Génération du token de vérification
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );

            // Envoi de l'email de vérification
            $user->sendEmailVerificationNotification();


            // Création du profil spécifique selon le rôle
            if ($validated['role'] === 'learner') {
                LearnerProfile::create([
                    'user_id' => $user->id,
                    'birthdate' => $validated['birthdate'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'postal_code' => $validated['postal_code'] ?? null,
                    'cin_number' => $validated['cin_number'] ?? null,
                    'cin_issue_date' => $validated['cin_issue_date'] ?? null,
                    'cin_issue_place' => $validated['cin_issue_place'] ?? null,
                    'permit_number' => $validated['permit_number'] ?? null,
                    'permit_issue_date' => $validated['permit_issue_date'] ?? null,
                    'permit_category' => $validated['permit_category'] ?? null,
                ]);
            } 
            
            if ($validated['role'] === 'instructor') {
                InstructorProfile::create([
                    'user_id' => $user->id,
                    'tva' => $validated['tva'] ? 20 : 0,
                    'address' => $validated['address'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'postal_code' => $validated['postal_code'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                    'certification_issue_date' => $validated['certification_issue_date'] ?? null,
                    'specialty' => $validated['specialty'] ?? null,
                    'certification_number' => $validated['certification_number'] ?? null,
                ]);
                Log::info([
                    'workZones' => $request->workZones,
                    'vehicles' => $request->vehicles
                ]);
                
                foreach($request->workZones as $zone){
                    MeetingPoint::create([
                        'instructor_id' => $user->id,
                        'label' => $zone['label'],
                        'address' => $zone['address'],
                        'city' => $zone['city'],
                        'postal_code' => $zone['postal_code'],
                        'latitude' => $zone['latitude'],
                        'longitude' => $zone['longitude'],
                        'is_active' => $zone['is_active'] ?? true,
                    ]);
                }
                foreach($request->vehicles as $vehicle){
                    if($vehicle['photo_url'])
                        $photoUrl = $vehicle['photo_url']->store('vehicule', 'public');
                    else 
                    $photoUrl = null;

                    $vehicle = Vehicle::create([
                        'instructor_id' => $user->id,
                        'brand' => $vehicle['brand'],
                        'model' => $vehicle['model'],
                        'year' => $vehicle['year'],
                        'plate_number' => $vehicle['plate_number'],
                        'fuel_type' => $vehicle['fuel_type'],
                        'insurance_expiry' => isset($vehicle['insurance_expiry']) ? $vehicle['insurance_expiry'] : null,
                        'technical_inspection_date' => isset($vehicle['technical_inspection_date']) ? $vehicle['technical_inspection_date'] : null,
                        'photo_url' => $photoUrl,
                        'color' => isset($vehicle['color']) ? $vehicle['color'] : null,
                        'gearbox_type' => $vehicle['gearbox_type'],
                        'status' => isset($vehicle['status']) ? $vehicle['color'] : 'available',
                    ]);

                }
            }
            // Pour le rôle 'admin', aucun profil supplémentaire n'est créé (ou ajoutez un modèle AdminProfile si nécessaire)
            DB::commit();

            // $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                // 'token' => $token,
                // 'data' => $user->load($validated['role'] === 'learner' ? 'learnerProfile' : ($validated['role'] === 'instructor' ? 'instructorProfile' : [])),
                'message' => 'Utilisateur inscrit avec succès'
            ], 201);

        }catch (Exception $th) {
            DB::rollBack();
            // Return a custom error response
            Log::error([
                'message' => $th->getMessage(),
                'error' => 'There was an error creating the user.'
            ]);
            return response()->json([
                'message' => $th->getMessage(),
                'error' => 'There was an error creating the user.'
            ], 500);
        } 
    }

    /**
     * Login
     * 
     */
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

        // if (!$user->hasVerifiedEmail()) {
        //     $user->sendEmailVerificationNotification();
        //     return response()->json(['message' => 'Compte non vérifié. Un mail a été envoyé sur votre email', "success"=>false], 403);
        // }

        // Générer un jeton
        $token = $user->createToken('auth-token')->plainTextToken;
        
        if($user->role == "learner"){
            $learner = LearnerProfile::where('user_id',$user->id)->first();
            return response()->json([
                'success' => true,
                'token' => $token,
                'test_passed' => $learner->test_passed,
                'data' => $user,
                'message' => 'Connexion réussie',
            ], 200);
        }
        else
            return response()->json([
                'success' => true,
                'token' => $token,
                'data' => $user,
                'message' => 'Connexion réussie',
            ], 200);
    }

    /**
     * Check Asking Question
     */
    public function checkAsk(Request $request){

        $validated = $request->validate([
            'point' => 'required',
        ]);

        $hour = $this->checkPoint($request->point);

        $learner = LearnerProfile::where('user_id',auth()->user()->id)->first();

        $learner->update([
            'test_passed' => true,
            'hour' => $hour,
        ]);

        $this->sendmailer( auth()->user()->id, 'Bilan du test', 'Bilan du test', 'Bonjour Mr/Mme, Lors de ce test, vous avez accumulé un total de '.$request->point.' points, ce qui equivaut à un total de '.$hour.' heures','bilan');

        return response()->json([
            'success' => true,
            'message' => 'Félicitation',
            'data' => $hour,
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

    public function emailVerify(Request $request)
    {
        try {
            // Vérification standard de la requête
            if (!$request->hasValidSignature()) {
                return response()->json(['message' => 'Lien de vérification invalide ou expiré'], 403);
            }

            $user = User::find($request->route('id'));

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }

            // Vérification du hash
            if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
                return response()->json(['message' => 'Lien de vérification invalide'], 403);
            }

            // Marquer l'email comme vérifié
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
                // event(new Verified($user));
            }

            // URL de redirection externe (à personnaliser)
            $frontendUrl = config('app.frontend_url') . "/connexion" . '/' .Str::random(10);
            // $frontendUrl = config('app.frontend_url') . '/connexion?email_verified=1&email=' . urlencode($user->email);
            
            // Redirection HTTP vers le frontend
            return redirect()->away($frontendUrl);
        } catch (\Exception $e) {
            // URL de fallback en cas d'erreur
            $fallbackUrl = config('app.frontend_url') . '/connexion';
            return redirect()->away($fallbackUrl);
        }
    }

    public function verificationNotification(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email'
        ]);
        $user = User::where('email', $request->email)->first();
        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Lien de vérification envoyé','success' => true]);
    }

    public function mailContact(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'object' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Envoyer l'email de contact
        $this->sendmailercontact( 'Formulaire de contact', 'Message de : '.$validated['firstname'].' '.$validated['lastname'].' ('.$validated['email'].')  '.$validated['phone'].'.  '.$validated['message'],$validated['object'],);

        return response()->json([
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons bientôt.',
        ], 200);
    }

}
