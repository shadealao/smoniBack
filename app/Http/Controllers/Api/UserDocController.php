<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserDocController extends Controller
{
    /**
     * Upload a document.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB max
        ]);

        $file = $request->file('file');
        $fileName = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('documents', $fileName, 'public');
        $fileType = $file->getClientMimeType();

        $userDoc = UserDoc::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'file' => $filePath,
            'file_type' => $fileType,
            'status' => true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $userDoc,
            'message' => 'Document téléchargé avec succès.',
        ], 201);
    }

    /**
     * Delete a document.
     */
    public function destroy(UserDoc $userDoc)
    {
        $user = Auth::user();

        if ($userDoc->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer ce document.',
            ], 403);
        }

        // Delete the file from storage
        if (Storage::disk('public')->exists($userDoc->file)) {
            Storage::disk('public')->delete($userDoc->file);
        }

        $userDoc->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document supprimé avec succès.',
        ], 200);
    }

    /**
     * List all documents for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        $userDocs = UserDoc::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'data' => $userDocs,
            'message' => 'Liste des documents récupérée avec succès.',
        ], 200);
    }
}