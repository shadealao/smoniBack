<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MeetingPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingPointController extends Controller
{
    /**
     * Create a new meeting point.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent créer des lieux de rendez-vous.',
            ], 403);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'sometimes|boolean',
        ]);

        $meetingPoint = MeetingPoint::create([
            'instructor_id' => $user->id,
            'label' => $validated['label'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $meetingPoint,
            'message' => 'Lieu de rendez-vous créé avec succès.',
        ], 201);
    }

    /**
     * View details of a meeting point.
     */
    public function show(MeetingPoint $meetingPoint)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' || $meetingPoint->instructor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à voir ce lieu de rendez-vous.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $meetingPoint,
            'message' => 'Détails du lieu de rendez-vous récupérés avec succès.',
        ], 200);
    }

    /**
     * Update a meeting point.
     */
    public function update(Request $request, MeetingPoint $meetingPoint)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' || $meetingPoint->instructor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce lieu de rendez-vous.',
            ], 403);
        }

        $validated = $request->validate([
            'label' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'sometimes|boolean',
        ]);

        $meetingPoint->update(array_filter($validated));

        return response()->json([
            'success' => true,
            'data' => $meetingPoint->fresh(),
            'message' => 'Lieu de rendez-vous mis à jour avec succès.',
        ], 200);
    }

    /**
     * Delete a meeting point.
     */
    public function destroy(MeetingPoint $meetingPoint)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' || $meetingPoint->instructor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer ce lieu de rendez-vous.',
            ], 403);
        }

        $meetingPoint->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lieu de rendez-vous supprimé avec succès.',
        ], 200);
    }

    /**
     * List all meeting points created by the instructor.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent voir leurs lieux de rendez-vous.',
            ], 403);
        }

        $meetingPoints = MeetingPoint::where('instructor_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'data' => $meetingPoints,
            'message' => 'Liste des lieux de rendez-vous récupérée avec succès.',
        ], 200);
    }

    /**
     * Recherche meeting points.
     */
    public function get_meeting_points(Request $request)
    {
        $request->validate([
            'search' => 'required',
        ]);

        $meetingPoints = MeetingPoint::whereRaw('LOWER(label) like ?', '%'. strtolower($request->search).'%')
            ->whereRaw('LOWER(address) like ?', '%'.strtolower($request->search).'%');

        return response()->json([
            'success' => true,
            // 'data' => $meetingPoints->get(),
            'countMeetingPoints' => $meetingPoints->count(),
            'message' => 'Liste des lieux de rendez-vous récupérée avec succès.',
        ], 200);
    }
    
}