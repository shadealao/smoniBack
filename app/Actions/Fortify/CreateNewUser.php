<?php

namespace App\Actions\Fortify;

use App\Models\InstructorProfile;
use App\Models\LearnerProfile;
use App\Models\MeetingPoint;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * Ports the prior AuthController::register logic. Behaviour preserved
     * except: password rule strengthened, OS-level validation now via
     * Validator facade (Fortify entrypoint), email verification triggered
     * automatically by Fortify's RegisterController (sends notification
     * if the user model implements MustVerifyEmail — User does).
     *
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): User
    {
        $validated = Validator::make($input, [
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => $this->passwordRules(),
            'phone' => ['required', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'genre' => ['required', Rule::in(['homme', 'femme', 'autre'])],
            'role' => ['required', Rule::in(['learner', 'instructor', 'admin'])],

            'birthdate' => ['required', 'date', 'before:today'],
            'address' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
            'cin_number' => ['nullable', 'string', 'max:255'],
            'cin_issue_date' => ['nullable', 'date'],
            'cin_issue_place' => ['nullable', 'string', 'max:50'],
            'permit_number' => ['nullable', 'string'],
            'permit_issue_date' => ['nullable', 'date'],
            'permit_category' => ['nullable', 'string'],

            'tva' => ['nullable', 'boolean'],
            'specialty' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'certification_issue_date' => ['nullable', 'date'],
            'certification_number' => ['nullable', 'numeric', 'min:0'],

            'workZones' => ['sometimes', 'array'],
            'workZones.*.label' => ['required_if:role,instructor', 'string', 'max:255'],
            'workZones.*.address' => ['nullable', 'string', 'max:255'],
            'workZones.*.city' => ['nullable', 'string', 'max:100'],
            'workZones.*.postal_code' => ['nullable', 'string', 'max:20'],
            'workZones.*.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'workZones.*.longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'vehicles' => ['sometimes', 'array'],
            'vehicles.*.brand' => ['required_if:role,instructor', 'string', 'max:100'],
            'vehicles.*.model' => ['required_if:role,instructor', 'string', 'max:100'],
            'vehicles.*.year' => ['required_if:role,instructor', 'integer', 'min:1900', 'max:' . date('Y')],
            'vehicles.*.plate_number' => ['required_if:role,instructor', 'string', 'max:20', 'unique:vehicles,plate_number'],
            'vehicles.*.fuel_type' => ['required_if:role,instructor', 'in:essence,diesel,électrique,hybride'],
            'vehicles.*.insurance_expiry' => ['nullable', 'date', 'after:today'],
            'vehicles.*.technical_inspection_date' => ['nullable', 'date', 'after:today'],
            'vehicles.*.photo_url' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'vehicles.*.color' => ['nullable', 'string', 'max:50'],
            'vehicles.*.gearbox_type' => ['required_if:role,instructor', 'in:manual,automatic'],
            'vehicles.*.status' => ['sometimes', 'in:available,maintenance,out_of_service'],
        ])->validate();

        return DB::transaction(function () use ($validated, $input) {
            $photoPath = isset($input['photo']) && $input['photo']
                ? $input['photo']->store('profil', 'public')
                : null;

            $user = User::create([
                'lastname' => $validated['lastname'],
                'firstname' => $validated['firstname'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'genre' => $validated['genre'],
                'role' => $validated['role'],
                'is_active' => $validated['role'] !== 'instructor',
                'photo' => $photoPath,
            ]);

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
                    'tva' => ($validated['tva'] ?? false) ? 20 : 0,
                    'address' => $validated['address'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'postal_code' => $validated['postal_code'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                    'certification_issue_date' => $validated['certification_issue_date'] ?? null,
                    'specialty' => $validated['specialty'] ?? null,
                    'certification_number' => $validated['certification_number'] ?? null,
                ]);

                foreach ($input['workZones'] ?? [] as $zone) {
                    MeetingPoint::create([
                        'instructor_id' => $user->id,
                        'label' => $zone['label'],
                        'address' => $zone['address'] ?? null,
                        'city' => $zone['city'] ?? null,
                        'postal_code' => $zone['postal_code'] ?? null,
                        'latitude' => $zone['latitude'] ?? null,
                        'longitude' => $zone['longitude'] ?? null,
                        'is_active' => $zone['is_active'] ?? true,
                    ]);
                }

                foreach ($input['vehicles'] ?? [] as $vehicle) {
                    $photoUrl = isset($vehicle['photo_url']) && $vehicle['photo_url']
                        ? $vehicle['photo_url']->store('vehicule', 'public')
                        : null;

                    Vehicle::create([
                        'instructor_id' => $user->id,
                        'brand' => $vehicle['brand'],
                        'model' => $vehicle['model'],
                        'year' => $vehicle['year'],
                        'plate_number' => $vehicle['plate_number'],
                        'fuel_type' => $vehicle['fuel_type'],
                        'insurance_expiry' => $vehicle['insurance_expiry'] ?? null,
                        'technical_inspection_date' => $vehicle['technical_inspection_date'] ?? null,
                        'photo_url' => $photoUrl,
                        'color' => $vehicle['color'] ?? null,
                        'gearbox_type' => $vehicle['gearbox_type'],
                        'status' => $vehicle['status'] ?? 'available',
                    ]);
                }
            }

            // Dev / preview / testing bypass: mark verified now so the
            // Registered listener's hasVerifiedEmail() guard skips the email.
            // Prod (flag false) leaves the user unverified → email is sent.
            if (config('auth.auto_verify_email')) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            return $user;
        });
    }
}
