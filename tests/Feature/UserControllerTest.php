<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LearnerProfile;
use App\Models\InstructorProfile;
use App\Models\OtpCode;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake(); // Mock notifications for testing
    }

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

    /**
     * Test sending OTP code successfully.
     */
    public function test_send_otp_code_success()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
        ]);

        $response = $this->postJson('/api/password/send-otp', [
            'email' => 'john.doe@example.com',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Code OTP envoyé à votre email.',
                 ]);

        $this->assertDatabaseHas('otp_codes', [
            'user_id' => $user->id,
            'is_used' => false,
        ]);

        Notification::assertSentTo($user, \App\Notifications\SendOtpCode::class);
    }

    /**
     * Test sending OTP code with non-existent email.
     */
    public function test_send_otp_code_fails_with_invalid_email()
    {
        $response = $this->postJson('/api/password/send-otp', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test verifying OTP code successfully.
     */
    public function test_verify_otp_code_success()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
        ]);

        $otp = OtpCode::create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false,
        ]);

        $response = $this->postJson('/api/password/verify-otp', [
            'email' => 'john.doe@example.com',
            'code' => '123456',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Code OTP vérifié avec succès.',
                 ])
                 ->assertJsonStructure(['success', 'message']);

        $this->assertDatabaseHas('otp_codes', [
            'id' => $otp->id,
            'is_used' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            // 'password_reset_token' => $response->json('reset_token'),
        ]);
    }

    /**
     * Test verifying invalid or expired OTP code.
     */
    public function test_verify_otp_code_fails_with_invalid_code()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
        ]);

        $response = $this->postJson('/api/password/verify-otp', [
            'email' => 'john.doe@example.com',
            'code' => '999999',
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Code OTP invalide ou expiré.',
                 ]);
    }

    /**
     * Test updating password successfully.
     */
    public function test_update_password_success()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => Hash::make('oldpassword'),
            // 'password_reset_token' => 'random_token_123',
        ]);

        $response = $this->postJson('/api/password/reset', [
            'email' => 'john.doe@example.com',
            // 'reset_token' => 'random_token_123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Mot de passe mis à jour avec succès.',
                 ]);

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
        // $this->assertNull($user->password_reset_token);
    }

    /**
     * Test updating password with invalid reset token.
     */
    public function test_update_password_fails_with_invalid_token()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            // 'password_reset_token' => 'random_token_123',
        ]);

        $response = $this->postJson('/api/password/reset', [
            'email' => 'john.doe@example.com',
            // 'reset_token' => 'wrong_token',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Token de réinitialisation invalide.',
                 ]);
    }
}