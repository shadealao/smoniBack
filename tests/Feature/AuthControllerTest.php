<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LearnerProfile;
use App\Models\InstructorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la base de données après chaque test

    /**
     * Test successful registration for a learner.
     */
    public function test_register_learner_success()
    {
        $response = $this->postJson('/api/register', [
            'lastname' => 'Doe',
            'firstname' => 'John',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'role' => 'learner',
            'birthdate' => '1995-05-15',
            'address' => '123 Main St',
            'postal_code' => '12345',
            'cin_number' => 'CIN123456',
            'cin_issue_date' => '2020-01-01',
            'cin_issue_place' => 'City Hall',
            'permit_number' => 'PERMIT123',
            'permit_issue_date' => '2021-06-01',
            'permit_category' => 'B',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'token',
                     'data' => [
                         'id', 'lastname', 'firstname', 'email', 'phone', 'role',
                         'learner_profile' => [
                             'birthdate', 'address', 'postal_code', 'cin_number',
                             'cin_issue_date', 'cin_issue_place', 'permit_number',
                             'permit_issue_date', 'permit_category',
                         ],
                     ],
                     'message',
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'Utilisateur inscrit avec succès',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'role' => 'learner',
        ]);

        $this->assertDatabaseHas('learner_profiles', [
            'user_id' => User::where('email', 'john.doe@example.com')->first()->id,
            'birthdate' => '1995-05-15 00:00:00',
            'permit_category' => 'B',
        ]);
    }

    /**
     * Test successful registration for an instructor.
     */
    public function test_register_instructor_success()
    {
        $response = $this->postJson('/api/register', [
            'lastname' => 'Smith',
            'firstname' => 'Emma',
            'email' => 'emma.smith@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'role' => 'instructor',
            'specialty' => 'Driving',
            'city' => 'New York',
            'address' => '456 Elm St',
            'postal_code' => '67890',
            'bio' => 'Experienced driving instructor',
            'certification_issue_date' => '2020-01-01',
            'certification_number' => 123456,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'token',
                     'data' => [
                         'id', 'lastname', 'firstname', 'email', 'phone', 'role',
                         'instructor_profile' => [
                             'address', 'city', 'postal_code', 'bio',
                             'certification_issue_date', 'specialty', 'certification_number',
                         ],
                     ],
                     'message',
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'Utilisateur inscrit avec succès',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'emma.smith@example.com',
            'role' => 'instructor',
        ]);

        $this->assertDatabaseHas('instructor_profiles', [
            'user_id' => User::where('email', 'emma.smith@example.com')->first()->id,
            'specialty' => 'Driving',
            'city' => 'New York',
        ]);
    }

    /**
     * Test successful registration for an admin.
     */
    public function test_register_admin_success()
    {
        $response = $this->postJson('/api/register', [
            'lastname' => 'Admin',
            'firstname' => 'Super',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'role' => 'admin',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'token',
                     'data' => [
                         'id', 'lastname', 'firstname', 'email', 'phone', 'role',
                     ],
                     'message',
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'Utilisateur inscrit avec succès',
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }

    /**
     * Test registration with invalid data (validation fails).
     */
    public function test_register_validation_fails()
    {
        $response = $this->postJson('/api/register', [
            'lastname' => '', // Missing required field
            'firstname' => 'John',
            'email' => 'invalid-email', // Invalid email
            'password' => 'short', // Too short
            'phone' => '+1234567890',
            'role' => 'learner',
            'birthdate' => 'invalid-date', // Invalid date
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'lastname', 'email', 'password', 'birthdate',
                 ]);
    }

    /**
     * Test registration with duplicate email.
     */
    public function test_register_fails_with_duplicate_email()
    {
        User::factory()->create([
            'email' => 'john.doe@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'lastname' => 'Doe',
            'firstname' => 'John',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+1234567890',
            'role' => 'learner',
            'birthdate' => '1995-05-15',
            'permit_category' => 'B',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test successful login.
     */
    public function test_login_success()
    {
        $user = User::factory()->create([
            'lastname' => 'Test',
            'firstname' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'phone' => '+1234567890',
            'role' => 'learner',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'token',
                     'data' => ['id', 'email', 'role'],
                     'message',
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'Connexion réussie',
                 ]);
    }

    /**
     * Test login with wrong credentials.
     */
    public function test_login_fails_with_wrong_credentials()
    {
        $user = User::factory()->create([
            'lastname' => 'Test',
            'firstname' => 'User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'phone' => '+1234567890',
            'role' => 'learner',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Identifiants incorrects',
                 ]);
    }
}