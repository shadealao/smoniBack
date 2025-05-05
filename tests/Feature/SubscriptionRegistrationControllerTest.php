<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Contract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionRegistrationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a subscription with payment and contract successfully.
     */
    public function test_store_subscription_success()
    {
        Storage::fake('public');
        $learner = User::factory()->create(['role' => 'learner']);
        $service = Service::factory()->create(['price' => 50000]); // 500.00 EUR
        Sanctum::actingAs($learner);

        $response = $this->postJson('/api/subscriptions', [
            'plan_id' => $service->id,
            'start_date' => '2025-05-06',
            'end_date' => '2026-05-06',
            'type_service' => 'on_site',
            'auto_renewal' => true,
            'payment_method' => 'credit_card',
            'amount' => 500.00,
            'currency' => 'EUR',
            'contract_file_original' => UploadedFile::fake()->create('contract.pdf', 100), // 100KB
            'contract_tag' => 'initial',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Abonnement créé avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'learner_id', 'plan_id', 'start_date', 'end_date', 'type_service',
                         'status', 'auto_renewal', 'payment_id', 'created_at', 'updated_at',
                         'plan', 'payment', 'contract',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('payments', [
            'user_id' => $learner->id,
            'amount' => 500.00,
            'currency' => 'EUR',
            'payment_method' => 'credit_card',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'learner_id' => $learner->id,
            'plan_id' => $service->id,
            'type_service' => 'on_site',
            'status' => 'active',
            'auto_renewal' => true,
        ]);

        $this->assertDatabaseHas('contracts', [
            'student_id' => $learner->id,
            'tag' => 'initial',
        ]);

        Storage::disk('public')->assertExists($response->json('data.contract.file_original'));
    }

    /**
     * Test creating a subscription with incorrect amount fails.
     */
    public function test_store_subscription_fails_with_incorrect_amount()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $service = Service::factory()->create(['price' => 50000]); // 500.00 EUR
        Sanctum::actingAs($learner);

        $response = $this->postJson('/api/subscriptions', [
            'plan_id' => $service->id,
            'start_date' => '2025-05-06',
            'end_date' => '2026-05-06',
            'type_service' => 'on_site',
            'auto_renewal' => true,
            'payment_method' => 'credit_card',
            'amount' => 400.00, // Incorrect amount
            'currency' => 'EUR',
            'contract_file_original' => UploadedFile::fake()->create('contract.pdf', 100),
            'contract_tag' => 'initial',
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Le montant du paiement ne correspond pas au prix du service.',
                 ]);
    }

    /**
     * Test recording payment status update successfully.
     */
    public function test_record_payment_success()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $learner = User::factory()->create(['role' => 'learner']);
        $payment = Payment::factory()->create(['user_id' => $learner->id]);
        $subscription = Subscription::factory()->create([
            'learner_id' => $learner->id,
            'payment_id' => $payment->id,
        ]);
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/payments/{$payment->id}/record", [
            'status' => 'completed',
            'payment_date' => '2025-05-06',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Paiement mis à jour avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => ['id', 'user_id', 'amount', 'currency', 'payment_date', 'status', 'subscription'],
                     'message',
                 ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'completed',
            'payment_date' => '2025-05-06 00:00:00',
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => 'active',
        ]);
    }

    /**
     * Test recording payment by non-admin fails.
     */
    public function test_record_payment_fails_for_non_admin()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $payment = Payment::factory()->create(['user_id' => $learner->id]);
        Sanctum::actingAs($learner);

        $response = $this->postJson("/api/payments/{$payment->id}/record", [
            'status' => 'completed',
            'payment_date' => '2025-05-06',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent mettre à jour les paiements.',
                 ]);
    }

    /**
     * Test updating a signed contract successfully.
     */
    public function test_update_contract_success()
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $learner = User::factory()->create(['role' => 'learner']);
        $subscription = Subscription::factory()->create(['learner_id' => $learner->id]);
        $contract = Contract::factory()->create([
            'subscription_id' => $subscription->id,
            'student_id' => $learner->id,
            'file_original' => 'contracts/original_test.pdf',
            'file_signed' => 'contracts/signed_test.pdf',
        ]);
        Storage::disk('public')->put('contracts/signed_test.pdf', 'test content');
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/contracts/{$contract->id}/update", [
            'file_signed' => UploadedFile::fake()->create('signed_contract.pdf', 100),
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Contrat signé mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('contracts', [
            'id' => $contract->id,
            'file_original' => 'contracts/original_test.pdf',
        ]);

        Storage::disk('public')->assertExists($response->json('data.file_signed'));
        Storage::disk('public')->assertMissing('contracts/signed_test.pdf');
    }

    /**
     * Test updating a contract by non-admin fails.
     */
    public function test_update_contract_fails_for_non_admin()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $subscription = Subscription::factory()->create(['learner_id' => $learner->id]);
        $contract = Contract::factory()->create([
            'subscription_id' => $subscription->id,
            'student_id' => $learner->id,
        ]);
        Sanctum::actingAs($learner);

        $response = $this->postJson("/api/contracts/{$contract->id}/update", [
            'file_signed' => UploadedFile::fake()->create('signed_contract.pdf', 100),
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les administrateurs peuvent mettre à jour les contrats.',
                 ]);
    }

    /**
     * Test listing subscriptions successfully.
     */
    public function test_index_subscriptions_success()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        $subscription = Subscription::factory()->create(['learner_id' => $learner->id]);
        Sanctum::actingAs($learner);

        $response = $this->getJson('/api/subscriptions');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des abonnements récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data');
    }

    /**
     * Test listing all subscriptions as admin.
     */
    public function test_index_subscriptions_as_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $subscription = Subscription::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/subscriptions');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des abonnements récupérée avec succès.',
                 ])
                 ->assertJsonCount(1, 'data');
    }
}