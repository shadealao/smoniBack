<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MeetingPoint;
use App\Models\Vehicle;
use App\Models\Availability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Carbon\Carbon;

class AvailabilityControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating an availability successfully.
     */
    public function test_store_availability_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $meetingPoint = MeetingPoint::factory()->create(['instructor_id' => $instructor->id]);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $date = Carbon::today()->addDays(1)->format('Y-m-d');
        $dayOfWeek = strtolower(Carbon::parse($date)->locale('fr')->dayName);

        $response = $this->postJson('/api/availabilities', [
            'meeting_point_id' => $meetingPoint->id,
            'vehicle_id' => $vehicle->id,
            'day_of_week' => $dayOfWeek,
            'date' => $date,
            'start_time' => '09:00',
            'end_time' => '11:00',
            'status' => true,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Disponibilité créée avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'instructor_id', 'meeting_point_id', 'vehicle_id',
                         'day_of_week', 'date', 'start_time', 'end_time', 'status',
                         'created_at', 'updated_at',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('availabilities', [
            'instructor_id' => $instructor->id,
            'meeting_point_id' => $meetingPoint->id,
            'vehicle_id' => $vehicle->id,
            'day_of_week' => $dayOfWeek,
            'date' => str($date) . ' 00:00:00',
        ]);
    }

    /**
     * Test creating an availability by non-instructor fails.
     */
    public function test_store_availability_fails_for_non_instructor()
    {
        $user = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/availabilities', [
            'meeting_point_id' => 1,
            'vehicle_id' => 1,
            'day_of_week' => 'lundi',
            'date' => '2025-05-01',
            'start_time' => '09:00',
            'end_time' => '11:00',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les moniteurs peuvent créer des disponibilités.',
                 ]);
    }

    /**
     * Test creating an availability with invalid meeting point fails.
     */
    public function test_store_availability_fails_with_invalid_meeting_point()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $meetingPoint = MeetingPoint::factory()->create(['instructor_id' => $otherInstructor->id]);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $date = Carbon::today()->addDays(1)->format('Y-m-d');
        $dayOfWeek = strtolower(Carbon::parse($date)->locale('fr')->dayName);

        $response = $this->postJson('/api/availabilities', [
            'meeting_point_id' => $meetingPoint->id,
            'vehicle_id' => $vehicle->id,
            'day_of_week' => $dayOfWeek,
            'date' => $date,
            'start_time' => '09:00',
            'end_time' => '11:00',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Le lieu de rendez-vous ou le véhicule spécifié n\'appartient pas à ce moniteur.',
                 ]);
    }

    /**
     * Test viewing an availability successfully.
     */
    public function test_show_availability_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $meetingPoint = MeetingPoint::factory()->create(['instructor_id' => $instructor->id]);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        $availability = Availability::factory()->create([
            'instructor_id' => $instructor->id,
            'meeting_point_id' => $meetingPoint->id,
            'vehicle_id' => $vehicle->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/availabilities/{$availability->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Détails de la disponibilité récupérés avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'instructor_id', 'meeting_point_id', 'vehicle_id',
                         'day_of_week', 'date', 'start_time', 'end_time', 'status',
                         'meeting_point', 'vehicle',
                     ],
                     'message',
                 ]);
    }

    /**
     * Test viewing an availability by non-owner fails.
     */
    public function test_show_availability_fails_for_non_owner()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $availability = Availability::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($otherInstructor);

        $response = $this->getJson("/api/availabilities/{$availability->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à voir cette disponibilité.',
                 ]);
    }

    /**
     * Test updating an availability successfully.
     */
    public function test_update_availability_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $meetingPoint = MeetingPoint::factory()->create(['instructor_id' => $instructor->id]);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        $availability = Availability::factory()->create([
            'instructor_id' => $instructor->id,
            'meeting_point_id' => $meetingPoint->id,
            'vehicle_id' => $vehicle->id,
        ]);
        Sanctum::actingAs($instructor);

        $newDate = Carbon::today()->addDays(2)->format('Y-m-d');
        $newDayOfWeek = strtolower(Carbon::parse($newDate)->locale('fr')->dayName);

        $response = $this->putJson("/api/availabilities/{$availability->id}", [
            'start_time' => '14:00',
            'end_time' => '16:00',
            'date' => $newDate,
            'day_of_week' => $newDayOfWeek,
            'status' => false,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Disponibilité mise à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('availabilities', [
            'id' => $availability->id,
            'start_time' => '14:00',
            'end_time' => '16:00',
            'date' => str($newDate) . ' 00:00:00',
            'status' => 1,
        ]);
    }

    /**
     * Test deleting an availability successfully.
     */
    public function test_destroy_availability_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $availability = Availability::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->deleteJson("/api/availabilities/{$availability->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Disponibilité supprimée avec succès.',
                 ]);

        $this->assertDatabaseMissing('availabilities', [
            'id' => $availability->id,
        ]);
    }

    /**
     * Test listing availabilities successfully.
     */
    public function test_index_availabilities_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Availability::factory()->count(3)->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson('/api/availabilities');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des disponibilités récupérée avec succès.',
                 ])
                 ->assertJsonCount(3, 'data');
    }

    /**
     * Test listing availabilities by non-instructor fails.
     */
    public function test_index_availabilities_fails_for_non_instructor()
    {
        $user = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/availabilities');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les moniteurs peuvent voir leurs disponibilités.',
                 ]);
    }
}