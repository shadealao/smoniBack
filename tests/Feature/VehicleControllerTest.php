<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VehicleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a vehicle successfully.
     */
    public function test_store_vehicle_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/vehicles', [
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'plate_number' => 'AB-123-CD',
            'fuel_type' => 'essence',
            'insurance_expiry' => '2026-12-31',
            'technical_inspection_date' => '2025-12-31',
            'photo_url' => 'https://example.com/vehicle.jpg',
            'color' => 'Blue',
            'gearbox_type' => 'manual',
            'status' => 'available',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Véhicule créé avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'instructor_id', 'brand', 'model', 'year',
                         'plate_number', 'fuel_type', 'insurance_expiry',
                         'technical_inspection_date', 'photo_url', 'color',
                         'gearbox_type', 'status', 'created_at', 'updated_at',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('vehicles', [
            'instructor_id' => $instructor->id,
            'brand' => 'Toyota',
            'plate_number' => 'AB-123-CD',
        ]);
    }

    /**
     * Test creating a vehicle by non-instructor fails.
     */
    public function test_store_vehicle_fails_for_non_instructor()
    {
        $user = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/vehicles', [
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'plate_number' => 'AB-123-CD',
            'fuel_type' => 'essence',
            'gearbox_type' => 'manual',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les moniteurs peuvent créer des véhicules.',
                 ]);
    }

    /**
     * Test viewing a vehicle successfully.
     */
    public function test_show_vehicle_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Détails du véhicule récupérés avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'instructor_id', 'brand', 'model', 'year',
                         'plate_number', 'fuel_type', 'insurance_expiry',
                         'technical_inspection_date', 'photo_url', 'color',
                         'gearbox_type', 'status', 'created_at', 'updated_at',
                     ],
                     'message',
                 ]);
    }

    /**
     * Test viewing a vehicle by non-owner fails.
     */
    public function test_show_vehicle_fails_for_non_owner()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($otherInstructor);

        $response = $this->getJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à voir ce véhicule.',
                 ]);
    }

    /**
     * Test updating a vehicle successfully.
     */
    public function test_update_vehicle_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->putJson("/api/vehicles/{$vehicle->id}", [
            'brand' => 'Honda',
            'model' => 'Civic',
            'status' => 'maintenance',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Véhicule mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'brand' => 'Honda',
            'model' => 'Civic',
            'status' => 'maintenance',
        ]);
    }

    /**
     * Test deleting a vehicle successfully.
     */
    public function test_destroy_vehicle_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $vehicle = Vehicle::factory()->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->deleteJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Véhicule supprimé avec succès.',
                 ]);

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
        ]);
    }

    /**
     * Test listing vehicles successfully.
     */
    public function test_index_vehicles_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Vehicle::factory()->count(3)->create(['instructor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des véhicules récupérée avec succès.',
                 ])
                 ->assertJsonCount(3, 'data');
    }

    /**
     * Test listing vehicles by non-instructor fails.
     */
    public function test_index_vehicles_fails_for_non_instructor()
    {
        $user = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/vehicles');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les moniteurs peuvent voir leurs véhicules.',
                 ]);
    }
}