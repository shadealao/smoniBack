<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TrainingModule;
use App\Models\ModuleStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TrainingModuleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a training module with steps successfully.
     */
    public function test_store_training_module_success()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/training-modules', [
            'name' => 'Driving Basics',
            'description' => 'Learn the fundamentals of driving.',
            'duration_hours' => 10,
            'required_for_license' => true,
            'display_order' => 1,
            'file' => UploadedFile::fake()->create('module.pdf', 100), // 100KB
            'is_active' => true,
            'steps' => [
                [
                    'name' => 'Introduction to Controls',
                    'description' => 'Learn car controls.',
                    'duration_minutes' => 60,
                    'step_type' => 'theory',
                    'display_order' => 1,
                    'required_for_completion' => true,
                ],
                [
                    'name' => 'Basic Maneuvers',
                    'description' => 'Practice basic driving.',
                    'duration_minutes' => 120,
                    'step_type' => 'practice',
                    'display_order' => 2,
                    'required_for_completion' => true,
                ],
            ],
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Module créé avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'name', 'description', 'duration_hours', 'required_for_license',
                         'display_order', 'file', 'is_active', 'created_at', 'updated_at',
                         'steps' => [
                             '*' => [
                                 'id', 'module_id', 'name', 'description', 'duration_minutes',
                                 'step_type', 'display_order', 'required_for_completion',
                                 'created_at', 'updated_at',
                             ],
                         ],
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('training_modules', [
            'name' => 'Driving Basics',
            'duration_hours' => 10,
            'required_for_license' => true,
        ]);

        $this->assertDatabaseHas('module_steps', [
            'name' => 'Introduction to Controls',
            'duration_minutes' => 60,
            'step_type' => 'theory',
        ]);

        Storage::disk('public')->assertExists($response->json('data.file'));
    }

    /**
     * Test creating a training module by non-admin fails.
     */
    public function test_store_training_module_fails_for_non_admin()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/training-modules', [
            'name' => 'Driving Basics',
            'duration_hours' => 10,
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent créer des modules.',
                 ]);
    }

    /**
     * Test listing training modules with steps.
     */
    public function test_index_training_modules_success()
    {
        $module = TrainingModule::factory()->create();
        ModuleStep::factory()->count(2)->create(['module_id' => $module->id]);

        $response = $this->getJson('/api/training-modules');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des modules récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data')
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id', 'name', 'description', 'duration_hours', 'required_for_license',
                             'display_order', 'file', 'is_active', 'created_at', 'updated_at',
                             'steps' => [
                                 '*' => [
                                     'id', 'module_id', 'name', 'description', 'duration_minutes',
                                     'step_type', 'display_order', 'required_for_completion',
                                     'created_at', 'updated_at',
                                 ],
                             ],
                         ],
                     ],
                     'message',
                 ]);
    }

    /**
     * Test updating a training module and its steps successfully.
     */
    public function test_update_training_module_success()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $module = TrainingModule::factory()->create();
        $step = ModuleStep::factory()->create(['module_id' => $module->id]);
        Sanctum::actingAs($admin);

        $response = $this->putJson("/api/training-modules/{$module->id}", [
            'name' => 'Advanced Driving',
            'duration_hours' => 15,
            'file' => UploadedFile::fake()->create('updated_module.pdf', 100),
            'steps' => [
                [
                    'id' => $step->id,
                    'name' => 'Updated Step',
                    'duration_minutes' => 90,
                    'step_type' => 'assessment',
                    'display_order' => 1,
                    'required_for_completion' => false,
                ],
                [
                    'name' => 'New Step',
                    'duration_minutes' => 45,
                    'step_type' => 'theory',
                    'display_order' => 2,
                    'required_for_completion' => true,
                ],
            ],
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Module mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('training_modules', [
            'id' => $module->id,
            'name' => 'Advanced Driving',
            'duration_hours' => 15,
        ]);

        $this->assertDatabaseHas('module_steps', [
            'id' => $step->id,
            'name' => 'Updated Step',
            'duration_minutes' => 90,
            'step_type' => 'assessment',
        ]);

        $this->assertDatabaseHas('module_steps', [
            'module_id' => $module->id,
            'name' => 'New Step',
            'duration_minutes' => 45,
        ]);

        Storage::disk('public')->assertExists($response->json('data.file'));
    }

    /**
     * Test updating a training module by non-admin fails.
     */
    public function test_update_training_module_fails_for_non_admin()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $module = TrainingModule::factory()->create();
        Sanctum::actingAs($instructor);

        $response = $this->putJson("/api/training-modules/{$module->id}", [
            'name' => 'Advanced Driving',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent modifier des modules.',
                 ]);
    }

    /**
     * Test deleting a training module successfully.
     */
    public function test_destroy_training_module_success()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $module = TrainingModule::factory()->create(['file' => 'modules/test.pdf']);
        $step = ModuleStep::factory()->create(['module_id' => $module->id]);
        Storage::disk('public')->put('modules/test.pdf', 'test content');
        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/training-modules/{$module->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Module supprimé avec succès.',
                 ]);

        $this->assertDatabaseMissing('training_modules', [
            'id' => $module->id,
        ]);

        $this->assertDatabaseMissing('module_steps', [
            'id' => $step->id,
        ]);

        Storage::disk('public')->assertMissing('modules/test.pdf');
    }

    /**
     * Test deleting a training module by non-admin fails.
     */
    public function test_destroy_training_module_fails_for_non_admin()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $module = TrainingModule::factory()->create();
        Sanctum::actingAs($instructor);

        $response = $this->deleteJson("/api/training-modules/{$module->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent supprimer des modules.',
                 ]);
    }
}