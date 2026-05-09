<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Availability;
use App\Models\Vehicle;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Carbon\Carbon;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test booking an appointment successfully.
     */
    public function test_store_appointment_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        $availability = Availability::factory()->create([
            'instructor_id' => $instructor->id,
            'vehicle_id' => $vehicle->id,
            'date' => Carbon::tomorrow(),
        ]);
        Sanctum::actingAs($learner);

        $response = $this->postJson('/api/appointments', [
            'availability_id' => $availability->id,
            'price' => 50.00,
            'tag' => 'Driving Lesson',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Rendez-vous réservé avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'learner_id', 'instructor_id', 'availability_id',
                         'vehicle_id', 'date', 'start_time', 'end_time',
                         'duration', 'status', 'price', 'tag',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('appointments', [
            'learner_id' => $learner->id,
            'instructor_id' => $instructor->id,
            'availability_id' => $availability->id,
            'status' => 'scheduled',
        ]);
    }

    /**
     * Test booking an appointment by non-learner fails.
     */
    public function test_store_appointment_fails_for_non_learner()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/appointments', [
            'availability_id' => 1,
            'price' => 50.00,
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les apprenants peuvent réserver des rendez-vous.',
                 ]);
    }

    /**
     * Test updating an appointment (lesson notes) successfully.
     */
    public function test_update_appointment_lesson_notes_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $appointment = Appointment::factory()->create([
            'learner_id' => $learner->id,
            'instructor_id' => $instructor->id,
        ]);
        Sanctum::actingAs($learner);

        $response = $this->putJson("/api/appointments/{$appointment->id}", [
            'lesson_notes' => ['note' => 'Good progress on turns'],
            'price' => 60.00,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Rendez-vous mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'price' => 60.00,
            'lesson_notes' => json_encode(['note' => 'Good progress on turns']),
        ]);
    }

    /**
     * Test updating an appointment (date/time) by instructor successfully.
     */
    public function test_update_appointment_date_time_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        $appointment = Appointment::factory()->create([
            'learner_id' => $learner->id,
            'instructor_id' => $instructor->id,
        ]);
        $newAvailability = Availability::factory()->create([
            'instructor_id' => $instructor->id,
            'vehicle_id' => $vehicle->id,
            'date' => Carbon::tomorrow()->addDays(1),
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->putJson("/api/appointments/{$appointment->id}", [
            'availability_id' => $newAvailability->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Rendez-vous mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'availability_id' => $newAvailability->id,
            'date' => $newAvailability->date,
        ]);
    }

    /**
     * Test canceling an appointment by instructor successfully.
     */
    public function test_cancel_appointment_by_instructor_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $appointment = Appointment::factory()->create([
            'learner_id' => $learner->id,
            'instructor_id' => $instructor->id,
            'date' => Carbon::tomorrow(),
            'status' => 'scheduled',
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson("/api/appointments/{$appointment->id}/cancel", [
            'cancellation_reason' => 'Instructor unavailable',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Rendez-vous annulé avec succès.',
                 ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled',
            'cancellation_reason' => 'Instructor unavailable',
        ]);
    }

    /**
     * Test canceling an appointment by learner within 24 hours fails.
     */
    public function test_cancel_appointment_by_learner_within_24_hours_fails()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $availability = Availability::factory()->create(['instructor_id' => $instructor->id]);
        $appointment = Appointment::factory()->create([
            'learner_id' => $learner->id,
            'instructor_id' => $instructor->id,
            'date' => $availability->date,
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'duration' => \Carbon\Carbon::parse($availability->end_time)->diffInMinutes($availability->start_time),
            'status' => 'scheduled',
        ]);
        Sanctum::actingAs($learner);

        $response = $this->postJson("/api/appointments/{$appointment->id}/cancel", [
            'cancellation_reason' => 'Learner unavailable',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Les apprenants ne peuvent annuler que 24 heures avant le rendez-vous.',
                 ]);
    }

    /**
     * Test marking presence successfully.
     */
    public function test_mark_presence_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $appointment = Appointment::factory()->create([
            'learner_id' => $learner->id,
            'instructor_id' => $instructor->id,
            'status' => 'scheduled',
        ]);
        Sanctum::actingAs($learner);

        $response = $this->postJson("/api/appointments/{$appointment->id}/presence");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Présence marquée avec succès.',
                 ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'presence_student' => true,
        ]);

        // Mark instructor presence
        Sanctum::actingAs($instructor);
        $response = $this->postJson("/api/appointments/{$appointment->id}/presence");

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'presence_monitor' => true,
            'status' => 'confirmed',
        ]);
    }

    /**
     * Test marking appointment as finished successfully.
     */
    public function test_mark_finished_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $appointment = Appointment::factory()->create([
            'learner_id' => $learner->id,
            'instructor_id' => $instructor->id,
            'status' => 'confirmed',
            'presence_student' => true,
            'presence_monitor' => true,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson("/api/appointments/{$appointment->id}/finished");

        $response->assertStatus(200)
                 ->assertJson([
                     ' Wednesday, October 29, 2025success' => true,
                     'message' => 'Rendez-vous marqué comme terminé avec succès.',
                 ]);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'finished' => true,
            'status' => 'completed',
        ]);
    }

    /**
     * Test marking appointment as finished without presence fails.
     */
    public function test_mark_finished_without_presence_fails()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $appointment = Appointment::factory()->create([
            'learner_id' => $learner->id,
            'instructor_id' => $instructor->id,
            'status' => 'scheduled',
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson("/api/appointments/{$appointment->id}/finished");

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Les deux parties doivent confirmer leur présence avant de marquer le rendez-vous comme terminé.',
                 ]);
    }
}