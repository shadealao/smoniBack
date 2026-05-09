<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MeetingPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MeetingPointControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a meeting point successfully.
     */
    public function test_store_meeting_point_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/meeting-points', [
            'label' => 'Main Station',
            'address' => '123 Station Road',
            'city' => 'New York',
            'postal_code' => '10001',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'is_active' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Lieu de rendez-vous créé avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'instructor_id', 'label', 'address', 'city',
                         'postal_code', 'latitude', 'longitude', 'is_active',
                         'created_at', 'updated_at',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('meeting_points', [
            'instructor_id' => $instructor->id,
            'label' => 'Main Station',
            'city' => 'New York',
        ]);
    }

    /**
     * Test creating a meeting point by non-instructor fails.
     */
    public function test_store_meeting_point_fails_for_non_instructor()
    {
        $user = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/meeting-points', [
            'label' => 'Main Station',
            'address' => '123 Station Road',
            'city' => 'New York',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les moniteurs peuvent créer des lieux de rendez-vous.',
                 ]);
    }

    /**
     * Test viewing a meeting point successfully.
     */
    public function test_show_meeting_point_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $meetingPoint = MeetingPoint::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/meeting-points/{$meetingPoint->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Détails du lieu de rendez-vous récupérés avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'instructor_id', 'label', 'address', 'city',
                         'postal_code', 'latitude', 'longitude', 'is_active',
                         'created_at', 'updated_at',
                     ],
                     'message',
                 ]);
    }

    /**
     * Test viewing a meeting point by non-owner fails.
     */
    public function test_show_meeting_point_fails_for_non_owner()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $meetingPoint = MeetingPoint::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($otherInstructor);

        $response = $this->getJson("/api/meeting-points/{$meetingPoint->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à voir ce lieu de rendez-vous.',
                 ]);
    }

    /**
     * Test updating a meeting point successfully.
     */
    public function test_update_meeting_point_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $meetingPoint = MeetingPoint::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->putJson("/api/meeting-points/{$meetingPoint->id}", [
            'label' => 'Updated Station',
            'address' => '456 New Road',
            'is_active' => false,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Lieu de rendez-vous mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('meeting_points', [
            'id' => $meetingPoint->id,
            'label' => 'Updated Station',
            'address' => '456 New Road',
            'is_active' => false,
        ]);
    }

    /**
     * Test deleting a meeting point successfully.
     */
    public function test_destroy_meeting_point_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $meetingPoint = MeetingPoint::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->deleteJson("/api/meeting-points/{$meetingPoint->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Lieu de rendez-vous supprimé avec succès.',
                 ]);

        $this->assertDatabaseMissing('meeting_points', [
            'id' => $meetingPoint->id,
        ]);
    }

    /**
     * Test listing meeting points successfully.
     */
    public function test_index_meeting_points_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        MeetingPoint::factory()->count(3)->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson('/api/meeting-points');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des lieux de rendez-vous récupérée avec succès.',
                 ])
                 ->assertJsonCount(3, 'data');
    }

    /**
     * Test listing meeting points by non-instructor fails.
     */
    public function test_index_meeting_points_fails_for_non_instructor()
    {
        $user = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/meeting-points');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les moniteurs peuvent voir leurs lieux de rendez-vous.',
                 ]);
    }
}