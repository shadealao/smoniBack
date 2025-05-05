<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ExamRegistration;
use App\Models\Note;
use App\Models\TrainingModule;
use App\Models\ModuleStep;
use App\Models\LearningHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExamNoteControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registering an exam successfully.
     */
    public function test_register_exam_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        LearningHistory::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/exam-registrations', [
            'learner_id' => $learner->id,
            'registration_date' => '2025-05-10',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Inscription à l\'examen créée avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'learner_id', 'monitor_id', 'registration_date', 'status',
                         'result_score', 'instructor_comments', 'created_at', 'updated_at',
                         'learner', 'monitor',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('exam_registrations', [
            'learner_id' => $learner->id,
            'monitor_id' => $instructor->id,
            'registration_date' => '2025-05-10 00:00:00',
            'status' => 'registered',
        ]);
    }

    /**
     * Test registering an exam by unauthorized instructor fails.
     */
    public function test_register_exam_fails_for_unauthorized_instructor()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/exam-registrations', [
            'learner_id' => $learner->id,
            'registration_date' => '2025-05-10',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                 ]);
    }

    /**
     * Test updating exam result successfully.
     */
    public function test_update_exam_result_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $examRegistration = ExamRegistration::factory()->create([
            'learner_id' => $learner->id,
            'monitor_id' => $instructor->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->putJson("/api/exam-registrations/{$examRegistration->id}", [
            'status' => 'passed',
            'result_score' => 85.50,
            'instructor_comments' => 'Excellent performance.',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Résultat de l\'examen mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('exam_registrations', [
            'id' => $examRegistration->id,
            'status' => 'passed',
            'result_score' => 85.50,
            'instructor_comments' => 'Excellent performance.',
        ]);
    }

    /**
     * Test updating exam result by unauthorized instructor fails.
     */
    public function test_update_exam_result_fails_for_unauthorized_instructor()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $examRegistration = ExamRegistration::factory()->create([
            'learner_id' => $learner->id,
            'monitor_id' => $instructor->id,
        ]);
        Sanctum::actingAs($otherInstructor);

        $response = $this->putJson("/api/exam-registrations/{$examRegistration->id}", [
            'status' => 'passed',
            'result_score' => 85.50,
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à mettre à jour cet examen.',
                 ]);
    }

    /**
     * Test creating a note successfully.
     */
    public function test_create_note_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $module = TrainingModule::factory()->create();
        $moduleStep = ModuleStep::factory()->create(['module_id' => $module->id]);
        LearningHistory::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/notes', [
            'student_id' => $learner->id,
            'module_id' => $module->id,
            'module_step_id' => $moduleStep->id,
            'comment' => 'Good progress on braking techniques.',
            'date' => '2025-05-05',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Note créée avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'monitor_id', 'student_id', 'module_id', 'module_step_id',
                         'comment', 'date', 'created_at', 'updated_at',
                         'monitor', 'student', 'module', 'module_step',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('notes', [
            'student_id' => $learner->id,
            'monitor_id' => $instructor->id,
            'module_id' => $module->id,
            'module_step_id' => $moduleStep->id,
            'comment' => 'Good progress on braking techniques.',
            'date' => '2025-05-05 00:00:00',
        ]);
    }

    /**
     * Test creating a note with invalid module step fails.
     */
    public function test_create_note_fails_with_invalid_module_step()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        $module = TrainingModule::factory()->create();
        $otherModule = TrainingModule::factory()->create();
        $moduleStep = ModuleStep::factory()->create(['module_id' => $otherModule->id]);
        LearningHistory::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/notes', [
            'student_id' => $learner->id,
            'module_id' => $module->id,
            'module_step_id' => $moduleStep->id,
            'comment' => 'Good progress.',
            'date' => '2025-05-05',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'L\'étape du module ne correspond pas au module spécifié.',
                 ]);
    }

    /**
     * Test listing exam registrations successfully.
     */
    public function test_list_exam_registrations_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        LearningHistory::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
        ]);
        ExamRegistration::factory()->create([
            'learner_id' => $learner->id,
            'monitor_id' => $instructor->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/learners/{$learner->id}/exam-registrations");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des inscriptions aux examens récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data');
    }

    /**
     * Test listing exam registrations by unauthorized user fails.
     */
    public function test_list_exam_registrations_fails_for_unauthorized_user()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/learners/{$learner->id}/exam-registrations");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                 ]);
    }

    /**
     * Test listing notes successfully.
     */
    public function test_list_notes_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        LearningHistory::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
        ]);
        Note::factory()->create([
            'monitor_id' => $instructor->id,
            'student_id' => $learner->id,
        ]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/learners/{$learner->id}/notes");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des notes récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data');
    }

    /**
     * Test listing notes by unauthorized user fails.
     */
    public function test_list_notes_fails_for_unauthorized_user()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $learner = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/learners/{$learner->id}/notes");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'avez pas enseigné cet apprenant.',
                 ]);
    }
}