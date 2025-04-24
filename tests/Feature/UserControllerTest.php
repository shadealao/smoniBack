<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LearnerProfile;
use App\Models\InstructorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test updating learner profile.
     */
    public function test_update_learner_profile_success()
    {
        $user = User::factory()->create(['role' => 'learner']);
        LearnerProfile::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile/update/learner', [
            'lastname' => 'Updated',
            'firstname' => 'John',
            'phone' => '+1234567890',
            'birthdate' => '1995-05-15',
            'address' => '123 New St',
            'permit_category' => 'B',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Profil apprenant mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'lastname' => 'Updated',
            'firstname' => 'John',
        ]);

        $this->assertDatabaseHas('learner_profiles', [
            'user_id' => $user->id,
            'birthdate' => '1995-05-15 00:00:00',
            'address' => '123 New St',
        ]);
    }

    /**
     * Test updating learner profile with wrong role.
     */
    public function test_update_learner_profile_fails_with_wrong_role()
    {
        $user = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile/update/learner', [
            'lastname' => 'Updated',
            'firstname' => 'John',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas un apprenant.',
                 ]);
    }

    /**
     * Test updating instructor profile.
     */
    public function test_update_instructor_profile_success()
    {
        $user = User::factory()->create(['role' => 'instructor']);
        InstructorProfile::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/profile/update/instructor', [
            'lastname' => 'Updated',
            'firstname' => 'Emma',
            'phone' => '+1234567890',
            'specialty' => 'Driving',
            'city' => 'New York',
            'certification_number' => 123456,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Profil moniteur mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'lastname' => 'Updated',
            'firstname' => 'Emma',
        ]);

        $this->assertDatabaseHas('instructor_profiles', [
            'user_id' => $user->id,
            'specialty' => 'Driving',
            'city' => 'New York',
        ]);
    }

    /**
     * Test viewing learner profile.
     */
    public function test_view_learner_profile_success()
    {
        $user = User::factory()->create(['role' => 'learner']);
        LearnerProfile::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/profile/learner');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Détails du profil apprenant récupérés avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'lastname', 'firstname', 'email', 'phone', 'role',
                         'learner_profile',
                     ],
                     'message',
                 ]);
    }

    /**
     * Test viewing instructor profile.
     */
    public function test_view_instructor_profile_success()
    {
        $user = User::factory()->create(['role' => 'instructor']);
        InstructorProfile::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/profile/instructor');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Détails du profil moniteur récupérés avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'lastname', 'firstname', 'email', 'phone', 'role',
                         'instructor_profile',
                     ],
                     'message',
                 ]);
    }

    /**
     * Test viewing admin profile.
     */
    public function test_view_admin_profile_success()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/profile/admin');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Détails du profil administrateur récupérés avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'lastname', 'firstname', 'email', 'phone', 'role',
                     ],
                     'message',
                 ]);
    }
}