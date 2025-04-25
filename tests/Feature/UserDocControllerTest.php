<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserDoc;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserDocControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test uploading a document successfully.
     */
    public function test_store_document_success()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->create('document.pdf', 100); // 100KB

        $response = $this->postJson('/api/user-docs', [
            'name' => 'Test Document',
            'file' => $file,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Document téléchargé avec succès.',
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'id', 'user_id', 'name', 'file', 'file_type', 'status',
                         'created_at', 'updated_at',
                     ],
                     'message',
                 ]);

        $this->assertDatabaseHas('user_docs', [
            'user_id' => $user->id,
            'name' => 'Test Document',
            'file_type' => 'application/pdf',
        ]);

        // Storage::disk('public')->assertExists('documents/' . $response->json('data.file'));
    }

    /**
     * Test uploading a document with invalid file type fails.
     */
    public function test_store_document_fails_with_invalid_file_type()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->create('document.txt', 100); // Invalid type

        $response = $this->postJson('/api/user-docs', [
            'name' => 'Test Document',
            'file' => $file,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['file']);
    }

    /**
     * Test deleting a document successfully.
     */
    public function test_destroy_document_success()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $userDoc = UserDoc::factory()->create([
            'user_id' => $user->id,
            'file' => 'documents/test.pdf',
        ]);
        Storage::disk('public')->put('documents/test.pdf', 'test content');
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/user-docs/{$userDoc->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Document supprimé avec succès.',
                 ]);

        $this->assertDatabaseMissing('user_docs', [
            'id' => $userDoc->id,
        ]);

        // Storage::disk('public')->assertMissing('documents/test.pdf');
    }

    /**
     * Test deleting a document by non-owner fails.
     */
    public function test_destroy_document_fails_for_non_owner()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $userDoc = UserDoc::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($otherUser);

        $response = $this->deleteJson("/api/user-docs/{$userDoc->id}");

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Vous n\'êtes pas autorisé à supprimer ce document.',
                 ]);
    }

    /**
     * Test listing documents successfully.
     */
    public function test_index_documents_success()
    {
        $user = User::factory()->create();
        UserDoc::factory()->count(3)->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user-docs');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Liste des documents récupérée avec succès.',
                 ])
                 ->assertJsonCount(3, 'data');
    }

    /**
     * Test listing documents only returns user’s documents.
     */
    public function test_index_documents_returns_only_user_documents()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        UserDoc::factory()->count(2)->create(['user_id' => $user->id]);
        UserDoc::factory()->create(['user_id' => $otherUser->id]);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user-docs');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }
}