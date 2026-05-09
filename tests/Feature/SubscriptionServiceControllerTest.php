<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\CategoryService;
use App\Models\SubCategoryService;
use App\Models\ServiceItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a service with category, sub-category, and items successfully.
     */
    public function test_store_service_success()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/services', [
            'category_label' => 'Driving Lessons',
            'sub_category_label' => 'Beginner',
            'title' => 'Basic Driving Package',
            'price' => 50000, // 500.00
            'items' => [
                ['label' => '10 Hours of Driving', 'status' => true],
                ['label' => 'Theory Lessons', 'status' => true],
            ],
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Service créé avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'category_service_id', 'sub_category_service_id', 'title', 'price',
                         'created_at', 'updated_at',
                         'category' => ['id', 'label'],
                         'sub_category' => ['id', 'label'],
                         'items' => [
                             '*' => ['id', 'service_id', 'label', 'status', 'created_at', 'updated_at'],
                         ],
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('category_services', [
            'label' => 'Driving Lessons',
        ]);

        $this->assertDatabaseHas('sub_category_services', [
            'label' => 'Beginner',
        ]);

        $this->assertDatabaseHas('services', [
            'title' => 'Basic Driving Package',
            'price' => 50000,
        ]);

        $this->assertDatabaseHas('service_items', [
            'label' => '10 Hours of Driving',
            'status' => true,
        ]);
    }

    /**
     * Test creating a service by non-admin fails.
     */
    public function test_store_service_fails_for_non_admin()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/services', [
            'category_label' => 'Driving Lessons',
            'sub_category_label' => 'Beginner',
            'title' => 'Basic Driving Package',
            'price' => 50000,
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent créer des services.',
                 ]);
    }

    /**
     * Test listing services with categories, sub-categories, and items.
     */
    public function test_index_services_success()
    {
        $service = Service::factory()->create();
        ServiceItem::factory()->count(2)->create(['service_id' => $service->id]);

        $response = $this->getJson('/api/services');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des services récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data')
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id', 'category_service_id', 'sub_category_service_id', 'title', 'price',
                             'created_at', 'updated_at',
                             'category' => ['id', 'label'],
                             'sub_category' => ['id', 'label'],
                             'items' => [
                                 '*' => ['id', 'service_id', 'label', 'status', 'created_at', 'updated_at'],
                             ],
                         ],
                     ],
                     'message',
                 ]);
    }

    /**
     * Test updating a service successfully.
     */
    public function test_update_service_success()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::factory()->create();
        $item = ServiceItem::factory()->create(['service_id' => $service->id]);
        Sanctum::actingAs($admin);

        $response = $this->putJson("/api/services/{$service->id}", [
            'category_label' => 'Advanced Lessons',
            'sub_category_label' => 'Expert',
            'title' => 'Advanced Driving Package',
            'price' => 75000,
            'items' => [
                [
                    'id' => $item->id,
                    'label' => 'Updated Item',
                    'status' => false,
                ],
                [
                    'label' => 'New Item',
                    'status' => true,
                ],
            ],
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Service mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('category_services', [
            'label' => 'Advanced Lessons',
        ]);

        $this->assertDatabaseHas('sub_category_services', [
            'label' => 'Expert',
        ]);

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'title' => 'Advanced Driving Package',
            'price' => 75000,
        ]);

        $this->assertDatabaseHas('service_items', [
            'id' => $item->id,
            'label' => 'Updated Item',
            'status' => false,
        ]);

        $this->assertDatabaseHas('service_items', [
            'service_id' => $service->id,
            'label' => 'New Item',
            'status' => true,
        ]);
    }

    /**
     * Test updating a service by non-admin fails.
     */
    public function test_update_service_fails_for_non_admin()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $service = Service::factory()->create();
        Sanctum::actingAs($instructor);

        $response = $this->putJson("/api/services/{$service->id}", [
            'title' => 'Advanced Driving Package',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent modifier des services.',
                 ]);
    }

    /**
     * Test deleting a service successfully.
     */
    public function test_destroy_service_success()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::factory()->create();
        $item = ServiceItem::factory()->create(['service_id' => $service->id]);
        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/services/{$service->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Service supprimé avec succès.',
                 ]);

        $this->assertDatabaseMissing('services', [
            'id' => $service->id,
        ]);

        $this->assertDatabaseMissing('service_items', [
            'id' => $item->id,
        ]);
    }

    /**
     * Test deleting a service by non-admin fails.
     */
    public function test_destroy_service_fails_for_non_admin()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $service = Service::factory()->create();
        Sanctum::actingAs($instructor);

        $response = $this->deleteJson("/api/services/{$service->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent supprimer des services.',
                 ]);
    }
}