<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WithdrawControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registering a withdrawal request successfully.
     */
    public function test_store_withdrawal_success()
    {
        Storage::fake('public');
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/withdraws', [
            'ammount' => 50000, // 500.00 EUR
            'duration' => 7,
            'currency' => 'EUR',
            'invoice_file' => UploadedFile::fake()->create('invoice.pdf', 100), // 100KB
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Demande de retrait enregistrée avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'monitor_id', 'ammount', 'duration', 'payed', 'currency',
                         'invoice_code', 'invoice_file', 'created_at', 'updated_at',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('withdraws', [
            'monitor_id' => $instructor->id,
            'ammount' => 50000,
            'duration' => 7,
            'currency' => 'EUR',
            'payed' => false,
        ]);

        // Storage::disk('public')->assertExists($response->json('data.invoice_file'));
    }

    /**
     * Test registering a withdrawal by non-instructor fails.
     */
    public function test_store_withdrawal_fails_for_non_instructor()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($learner);

        $response = $this->postJson('/api/withdraws', [
            'ammount' => 50000,
            'duration' => 7,
            'currency' => 'EUR',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les moniteurs peuvent demander des retraits.',
                 ]);
    }

    /**
     * Test viewing withdrawal requests by admin successfully.
     */
    public function test_index_withdrawals_success_for_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        Withdraw::factory()->count(3)->create(['monitor_id' => $instructor->id]);
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/withdraws');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des demandes de retrait récupérée avec succès.',
                 ])
                 ->assertJsonCount(3, 'data');
    }

    /**
     * Test viewing withdrawal requests by non-admin fails.
     */
    public function test_index_withdrawals_fails_for_non_admin()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->getJson('/api/withdraws');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent voir les demandes de retrait.',
                 ]);
    }

    /**
     * Test approving a withdrawal request successfully.
     */
    public function test_approve_withdrawal_success()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        $withdraw = Withdraw::factory()->create([
            'monitor_id' => $instructor->id,
            'payed' => false,
        ]);
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/withdraws/{$withdraw->id}/approve");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Demande de retrait approuvée avec succès.',
                 ]);

        $this->assertDatabaseHas('withdraws', [
            'id' => $withdraw->id,
            'payed' => true,
        ]);
    }

    /**
     * Test approving a withdrawal by non-admin fails.
     */
    public function test_approve_withdrawal_fails_for_non_admin()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $withdraw = Withdraw::factory()->create(['monitor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->postJson("/api/withdraws/{$withdraw->id}/approve");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent approuver les retraits.',
                 ]);
    }

    /**
     * Test approving an already paid withdrawal fails.
     */
    public function test_approve_already_paid_withdrawal_fails()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $instructor = User::factory()->create(['role' => 'instructor']);
        $withdraw = Withdraw::factory()->create([
            'monitor_id' => $instructor->id,
            'payed' => true,
        ]);
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/withdraws/{$withdraw->id}/approve");

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Cette demande de retrait a déjà été payée.',
                 ]);
    }
}