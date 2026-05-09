<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\BankAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BankAccountControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registering a bank account successfully.
     */
    public function test_store_bank_account_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        Sanctum::actingAs($instructor);

        $response = $this->postJson('/api/bank-accounts', [
            'iban' => 'FR7612345678901234567890123',
            'bic' => 'BNPAFRPPXXX',
            'bank_name' => 'BNP Paribas',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Compte bancaire enregistré avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'monitor_id', 'iban', 'bic', 'bank_name', 'status',
                         'created_at', 'updated_at',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('bank_accounts', [
            'monitor_id' => $instructor->id,
            'iban' => 'FR7612345678901234567890123',
            'bic' => 'BNPAFRPPXXX',
            'bank_name' => 'BNP Paribas',
            'status' => true,
        ]);
    }

    /**
     * Test registering a bank account by non-instructor fails.
     */
    public function test_store_bank_account_fails_for_non_instructor()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($learner);

        $response = $this->postJson('/api/bank-accounts', [
            'iban' => 'FR7612345678901234567890123',
            'bic' => 'BNPAFRPPXXX',
            'bank_name' => 'BNP Paribas',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les moniteurs peuvent enregistrer des comptes bancaires.',
                 ]);
    }

    /**
     * Test viewing bank account details successfully.
     */
    public function test_show_bank_account_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $bankAccount = BankAccount::factory()->create(['monitor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson("/api/bank-accounts/{$bankAccount->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Détails du compte bancaire récupérés avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'monitor_id', 'iban', 'bic', 'bank_name', 'status',
                         'created_at', 'updated_at',
                     ],
                     'message',
                 ]);
    }

    /**
     * Test viewing bank account details by non-owner fails.
     */
    public function test_show_bank_account_fails_for_non_owner()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $bankAccount = BankAccount::factory()->create(['monitor_id' => $instructor->id]);
        Sanctum::actingAs($otherInstructor);

        $response = $this->getJson("/api/bank-accounts/{$bankAccount->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à voir ce compte bancaire.',
                 ]);
    }

    /**
     * Test listing bank accounts successfully.
     */
    public function test_index_bank_accounts_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        BankAccount::factory()->count(3)->create(['monitor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->getJson('/api/bank-accounts');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des comptes bancaires récupérée avec succès.',
                 ])
                 ->assertJsonCount(3, 'data');
    }

    /**
     * Test listing bank accounts by non-instructor fails.
     */
    public function test_index_bank_accounts_fails_for_non_instructor()
    {
        $learner = User::factory()->create(['role' => 'learner']);
        Sanctum::actingAs($learner);

        $response = $this->getJson('/api/bank-accounts');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Seuls les moniteurs peuvent voir la liste des comptes bancaires.',
                 ]);
    }

    /**
     * Test deleting a bank account successfully.
     */
    public function test_destroy_bank_account_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $bankAccount = BankAccount::factory()->create(['monitor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->deleteJson("/api/bank-accounts/{$bankAccount->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Compte bancaire supprimé avec succès.',
                 ]);

        $this->assertDatabaseMissing('bank_accounts', [
            'id' => $bankAccount->id,
        ]);
    }

    /**
     * Test deleting a bank account by non-owner fails.
     */
    public function test_destroy_bank_account_fails_for_non_owner()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $bankAccount = BankAccount::factory()->create(['monitor_id' => $instructor->id]);
        Sanctum::actingAs($otherInstructor);

        $response = $this->deleteJson("/api/bank-accounts/{$bankAccount->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à supprimer ce compte bancaire.',
                 ]);
    }

    /**
     * Test updating a bank account successfully.
     */
    public function test_update_bank_account_success()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $bankAccount = BankAccount::factory()->create(['monitor_id' => $instructor->id]);
        Sanctum::actingAs($instructor);

        $response = $this->putJson("/api/bank-accounts/{$bankAccount->id}", [
            'iban' => 'FR7698765432109876543210987',
            'bic' => 'SOGEFRPPXXX',
            'bank_name' => 'Société Générale',
            'status' => false,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Compte bancaire mis à jour avec succès.',
                 ]);

        $this->assertDatabaseHas('bank_accounts', [
            'id' => $bankAccount->id,
            'iban' => 'FR7698765432109876543210987',
            'bic' => 'SOGEFRPPXXX',
            'bank_name' => 'Société Générale',
            'status' => false,
        ]);
    }

    /**
     * Test updating a bank account by non-owner fails.
     */
    public function test_update_bank_account_fails_for_non_owner()
    {
        $instructor = User::factory()->create(['role' => 'instructor']);
        $otherInstructor = User::factory()->create(['role' => 'instructor']);
        $bankAccount = BankAccount::factory()->create(['monitor_id' => $instructor->id]);
        Sanctum::actingAs($otherInstructor);

        $response = $this->putJson("/api/bank-accounts/{$bankAccount->id}", [
            'iban' => 'FR7698765432109876543210987',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à modifier ce compte bancaire.',
                 ]);
    }
}