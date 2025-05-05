<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SupportTicket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SupportTicketControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a support ticket successfully.
     */
    public function test_store_support_ticket_success()
    {
        $user = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/support-tickets', [
            'subject' => 'Issue with payment',
            'message' => 'I cannot process my payment for the subscription.',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Ticket de support créé avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'user_id', 'subject', 'message', 'status',
                         'assigned_to', 'resolved_at', 'created_at', 'updated_at',
                         'user', 'assignee',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $user->id,
            'subject' => 'Issue with payment',
            'status' => 'open',
            'assigned_to' => null,
            'resolved_at' => null,
        ]);
    }

    /**
     * Test listing support tickets successfully.
     */
    public function test_index_support_tickets_success()
    {
        $user = User::factory()->create(['role' => 'learner']);
        SupportTicket::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/support-tickets');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des tickets de support récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data');
    }

    /**
     * Test listing all support tickets as admin.
     */
    public function test_index_all_support_tickets_as_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        SupportTicket::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/support-tickets');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des tickets de support récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data');
    }

    /**
     * Test assigning a support ticket successfully.
     */
    public function test_assign_support_ticket_success()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $assignee = User::factory()->create(['role' => 'admin']);
        $ticket = SupportTicket::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/support-tickets/{$ticket->id}/assign", [
            'assigned_to' => $assignee->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Ticket assigné avec succès.',
                 ]);

        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'assigned_to' => $assignee->id,
            'status' => 'in_progress',
        ]);
    }

    /**
     * Test assigning a support ticket to non-admin fails.
     */
    public function test_assign_support_ticket_fails_for_non_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $learner = User::factory()->create(['role' => 'learner']);
        $ticket = SupportTicket::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/support-tickets/{$ticket->id}/assign", [
            'assigned_to' => $learner->id,
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Le ticket ne peut être assigné qu\'à un administrateur.',
                 ]);
    }

    /**
     * Test updating support ticket status successfully.
     */
    public function test_update_support_ticket_status_success()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = SupportTicket::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->putJson("/api/support-tickets/{$ticket->id}/status", [
            'status' => 'resolved',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Statut du ticket mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'resolved',
        ]);

        $this->assertNotNull($ticket->fresh()->resolved_at);
    }

    /**
     * Test updating support ticket status by non-admin fails.
     */
    public function test_update_support_ticket_status_fails_for_non_admin()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $ticket = SupportTicket::factory()->create();
        Sanctum::actingAs($learner);

        $response = $this->putJson("/api/support-tickets/{$ticket->id}/status", [
            'status' => 'resolved',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent mettre à jour le statut des tickets.',
                 ]);
    }

    /**
     * Test adding a response to a support ticket successfully.
     */
    public function test_add_response_to_support_ticket_success()
    {
        $user = User::factory()->create(['role' => 'learner']);
        $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/support-tickets/{$ticket->id}/response", [
            'message' => 'Any updates on my issue?',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Réponse ajoutée avec succès.',
                 ]);

        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'in_progress',
        ]);

        $this->assertStringContainsString('Any updates on my issue?', $ticket->fresh()->message);
    }

    /**
     * Test adding a response by unauthorized user fails.
     */
    public function test_add_response_to_support_ticket_fails_for_unauthorized_user()
    {
        $user = User::factory()->create(['role' => 'learner']);
        $otherUser = User::factory()->create(['role' => 'learner']);
        $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($otherUser);

        $response = $this->postJson("/api/support-tickets/{$ticket->id}/response", [
            'message' => 'Trying to respond.',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à répondre à ce ticket.',
                 ]);
    }
}