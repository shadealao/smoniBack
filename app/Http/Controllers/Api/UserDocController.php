<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserDoc;
use App\Models\InstructorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserDocController extends Controller
{
    /**
     * Upload a document.
     * @requestMediaType multipart/form-data
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB max
        ]);

        $exists = UserDoc::where('user_id', $user->id)
        ->where('name', $validated['name'])
        ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Un document avec ce nom existe déjà pour cet utilisateur.'
            ], 409);
        }

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
     * Save info Doc.
     */
    public function save_doc(Request $request)
    {
        $validated = $request->validate([
            'juridic_form' => 'required|string|max:255',
            'siret' => 'required|string|max:255',
            'num_activity' => 'required|string|max:255',
            'num_tva' => 'required|string|max:255',
            'num_teach_authorization' => 'required|string|max:255',
            'date_teach_permit' => 'required',
            'date_medical_visit' => 'required',
            'certification_number' => 'sometimes|string',
            'certification_issue_date' => 'sometimes',
        ]);

        $exist = InstructorProfile::where('user_id',auth()->user()->id)->first();

        if($exist)
            $exist->update([
                'juridic_form' => $request->juridic_form,
                'siret' => $request->siret,
                'num_activity' => $request->num_activity,
                'num_tva' => $request->num_tva,
                'num_teach_authorization' => $request->num_teach_authorization,
                'date_teach_permit' => $request->date_teach_permit,
                'date_medical_visit' => $request->date_medical_visit,
                'certification_number' => $validated['certification_number'] ?? $request->certification_number,
                'certification_issue_date' => $validated['certification_issue_date'] ?? $request->certification_issue_date 
            ]);
        else 
            $create = InstructorProfile::create([
                'user_id' => auth()->user()->id,
                'juridic_form' => $request->juridic_form,
                'siret' => $request->siret,
                'num_activity' => $request->num_activity,
                'num_tva' => $request->num_tva,
                'num_teach_authorization' => $request->num_teach_authorization,
                'date_teach_permit' => $request->date_teach_permit,
                'date_medical_visit' => $request->date_medical_visit,
                'certification_number' => $validated['certification_number'] ?? $request->certification_number,
                'certification_issue_date' => $validated['certification_issue_date'] ?? $request->certification_issue_date
            ]);

        return response()->json([
            'success' => true,
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
            'info' => auth()->user()->instructorProfile,
            'data' => $userDocs,
            'message' => 'Liste des documents récupérée avec succès.',
        ], 200);
    }
}