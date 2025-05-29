<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Create a new vehicle.
     * 
     * * @param Request $request
     * 
     * @requestMediaType multipart/form-data
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent créer des véhicules.',
            ], 403);
        }

        $validated = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'fuel_type' => 'required|in:essence,diesel,électrique,hybride',
            'insurance_expiry' => 'nullable|date|after:today',
            'technical_inspection_date' => 'nullable|date|after:today',
            'photo_url' => 'nullable|file',
            'color' => 'nullable|string|max:50',
            'gearbox_type' => 'required|in:manual,automatic',
            'status' => 'sometimes|in:available,maintenance,out_of_service',
        ]);

        if($request->hasFile('photo'))
            $photoUrl = $request->file('photo_url')->store('vehicule', 'public');
        else 
        $photoUrl = null;

        $vehicle = Vehicle::create([
            'instructor_id' => $user->id,
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'plate_number' => $validated['plate_number'],
            'fuel_type' => $validated['fuel_type'],
            'insurance_expiry' => $validated['insurance_expiry'],
            'technical_inspection_date' => $validated['technical_inspection_date'],
            'photo_url' => $photoUrl,
            'color' => $validated['color'],
            'gearbox_type' => $validated['gearbox_type'],
            'status' => $validated['status'] ?? 'available',
        ]);

        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => 'Véhicule créé avec succès.',
        ], 201);
    }

    /**
     * View details of a vehicle.
     */
    public function show(Vehicle $vehicle)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' || $vehicle->instructor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à voir ce véhicule.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $vehicle,
            'message' => 'Détails du véhicule récupérés avec succès.',
        ], 200);
    }

    /**
     * Update a vehicle.
     * 
     * * @param Request $request
     * 
     * @requestMediaType multipart/form-data
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' || $vehicle->instructor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce véhicule.',
            ], 403);
        }

        $validated = $request->validate([
            'brand' => 'sometimes|string|max:100',
            'model' => 'sometimes|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'plate_number' => 'sometimes|string|max:20|unique:vehicles,plate_number,' . $vehicle->id,
            'fuel_type' => 'sometimes|in:essence,diesel,électrique,hybride',
            'insurance_expiry' => 'nullable|date|after:today',
            'technical_inspection_date' => 'nullable|date|after:today',
            'photo_url' => 'nullable|file',
            'color' => 'nullable|string|max:50',
            'gearbox_type' => 'sometimes|in:manual,automatic',
            'status' => 'sometimes|in:available,maintenance,out_of_service',
        ]);

        
        if($request->hasFile('photo'))
            $photoUrl = $request->file('photo_url')->store('vehicule', 'public');
        else 
            $photoUrl = $vehicle->photo_url;

        $vehicle->update([
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'plate_number' => $validated['plate_number'],
            'fuel_type' => $validated['fuel_type'],
            'insurance_expiry' => $validated['insurance_expiry'],
            'technical_inspection_date' => $validated['technical_inspection_date'],
            'photo_url' => $photoUrl,
            'color' => $validated['color'],
            'gearbox_type' => $validated['gearbox_type'],
            'status' => $validated['status'] ?? 'available',
        ]);

        return response()->json([
            'success' => true,
            'data' => $vehicle->fresh(),
            'message' => 'Véhicule mis à jour avec succès.',
        ], 200);
    }

    /**
     * Delete a vehicle.
     */
    public function destroy(Vehicle $vehicle)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' || $vehicle->instructor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer ce véhicule.',
            ], 403);
        }

        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Véhicule supprimé avec succès.',
        ], 200);
    }

    /**
     * List all vehicles created by the instructor.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent voir leurs véhicules.',
            ], 403);
        }

        $vehicles = Vehicle::where('instructor_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'data' => $vehicles,
            'message' => 'Liste des véhicules récupérée avec succès.',
        ], 200);
    }
}