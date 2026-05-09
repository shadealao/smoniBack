<?php
// tests/Feature/EvaluationApiTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_evaluation()
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->postJson("/api/students/{$student->id}/evaluations", [
                'attitude_control_priority' => true,
                'attitude_learning_desire' => true,
                // ... autres champs requis
            ]);
            
        $response->assertCreated();
        $this->assertDatabaseHas('evaluations', ['student_id' => $student->id]);
    }
    
    public function test_accept_proposal()
    {
        $user = User::factory()->create(['role' => 'instructor']);
        $student = User::factory()->create();
        $evaluation = $student->evaluations()->create([
            'instructor_id' => $user->id,
            'proposal_accepted' => false,
            // ... autres champs
        ]);
        
        $response = $this->actingAs($user)
            ->postJson("/api/students/{$student->id}/evaluations/{$evaluation->id}/accept-proposal");
            
        $response->assertOk();
        $this->assertTrue($evaluation->fresh()->proposal_accepted);
    }
}