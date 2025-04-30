<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LearningHistory;
use App\Models\LearnerProgres;
use App\Models\Badge;
use App\Models\TrainingModule;
use App\Models\ModuleStep;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LearnerProgressControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test tracking learner progress successfully.
     */
    public function test_track_progress_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $module = TrainingModule::factory()->create();
        $step = ModuleStep::factory()->create(['module_id' => $module->id]);
        $appointment = Appointment::factory()->create([
            'instructor_id' => $instructor->id,
            'learner_id' => $learner->id,
        ]);
        $learningHistory = LearningHistory::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
            'appointment_id' => $appointment->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson("/api/learning-histories/{$learningHistory->id}/track", [
            'module_id' => $module->id,
            'current_step_id' => $step->id,
            'instructor_notes' => 'Good progress on controls.',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Progression mise à jour avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'learner_id', 'module_id', 'current_step_id', 'status',
                         'started_at', 'completed_at', 'instructor_notes',
                         'module', 'current_step',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('learner_progres', [
            'learner_id' => $learner->id,
            'module_id' => $module->id,
            'current_step_id' => $step->id,
            'status' => 'completed',
            'instructor_notes' => 'Good progress on controls.',
        ]);

        $this->assertDatabaseHas('learning_histories', [
            'id' => $learningHistory->id,
            'status' => true,
        ]);
    }

    /**
     * Test tracking progress by unauthorized instructor fails.
     */
    public function test_track_progress_fails_for_unauthorized_instructor()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $module = TrainingModule::factory()->create();
        $step = ModuleStep::factory()->create(['module_id' => $module->id]);
        $appointment = Appointment::factory()->create();
        $learningHistory = LearningHistory::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
            'appointment_id' => $appointment->id,
        ]);
        Sanctum::actingAs($otherInstructor);

        $response = $this->postJson("/api/learning-histories/{$learningHistory->id}/track", [
            'module_id' => $module->id,
            'current_step_id' => $step->id,
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à mettre à jour cette progression.',
                 ]);
    }

    /**
     * Test awarding a badge successfully.
     */
    public function test_award_badge_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $module = TrainingModule::factory()->create();
        $progress = LearnerProgres::factory()->create([
            'learner_id' => $learner->id,
            'module_id' => $module->id,
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson("/api/learner-progress/{$progress->id}/badge", [
            'certification_url' => 'https://example.com/cert.pdf',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Badge attribué avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'learner_id', 'module_id', 'awarded_at',
                         'validation_instructor_id', 'certification_url',
                         'module', 'validation_instructor',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('badges', [
            'learner_id' => $learner->id,
            'module_id' => $module->id,
            'validation_instructor_id' => $instructor->id,
            'certification_url' => 'https://example.com/cert.pdf',
        ]);
    }

    /**
     * Test awarding a badge for non-completed progress fails.
     */
    public function test_award_badge_fails_for_non_completed_progress()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $module = TrainingModule::factory()->create();
        $progress = LearnerProgres::factory()->create([
            'learner_id' => $learner->id,
            'module_id' => $module->id,
            'status' => 'in_progress',
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson("/api/learner-progress/{$progress->id}/badge");

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Le module doit être complété pour attribuer un badge.',
                 ]);
    }

    /**
     * Test listing badges for a learner successfully.
     */
    public function test_list_badges_success()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        $module = TrainingModule::factory()->create();
        Badge::factory()->create([
            'learner_id' => $learner->id,
            'module_id' => $module->id,
            'validation_instructor_id' => $instructor->id,
        ]);
        LearningHistory::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/learners/{$learner->id}/badges");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des badges récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data');
    }

    /**
     * Test listing badges by unauthorized user fails.
     */
    public function test_list_badges_fails_for_unauthorized_user()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/learners/{$learner->id}/badges");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                 ]);
    }

    /**
     * Test listing learner progress successfully.
     */
    public function test_list_progress_success()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        $module = TrainingModule::factory()->create();
        $step = ModuleStep::factory()->create(['module_id' => $module->id]);
        LearnerProgres::factory()->create([
            'learner_id' => $learner->id,
            'module_id' => $module->id,
            'current_step_id' => $step->id,
        ]);
        LearningHistory::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/learners/{$learner->id}/progress");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Progression de l\'apprenant récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data');
    }

    /**
     * Test listing progress by unauthorized user fails.
     */
    public function test_list_progress_fails_for_unauthorized_user()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/learners/{$learner->id}/progress");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                 ]);
    }
}