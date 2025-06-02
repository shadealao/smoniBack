<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\MeetingPoint;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Create a new availability.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent créer des disponibilités.',
            ], 403);
        }

        $validated = $request->validate([
            'meeting_point_id' => 'required|exists:meeting_points,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'day_of_week' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'sometimes|boolean',
        ]);

        // Verify that the meeting point and vehicle belong to the instructor
        $meetingPoint = MeetingPoint::where('id', $validated['meeting_point_id'])
                                   ->where('instructor_id', $user->id)
                                   ->first();
        $vehicle = Vehicle::where('id', $validated['vehicle_id'])
                          ->where('instructor_id', $user->id)
                          ->first();

        if (!$meetingPoint || !$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Le lieu de rendez-vous ou le véhicule spécifié n\'appartient pas à ce moniteur.',
            ], 403);
        }

        // Verify that the day_of_week matches the date
        $dateDayOfWeek = strtolower(\Carbon\Carbon::parse($validated['date'])->locale('fr')->dayName);
        if ($dateDayOfWeek !== $validated['day_of_week']) {
            return response()->json([
                'success' => false,
                'message' => 'Le jour de la semaine ne correspond pas à la date spécifiée.',
            ], 422);
        }

        $availability = Availability::create([
            'instructor_id' => $user->id,
            'meeting_point_id' => $validated['meeting_point_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'day_of_week' => $validated['day_of_week'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => $validated['status'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $availability,
            'message' => 'Disponibilité créée avec succès.',
        ], 201);
    }

    /**
     * View details of an availability.
     */
    public function show(Availability $availability)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' || $availability->instructor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à voir cette disponibilité.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $availability->load(['meetingPoint', 'vehicle']),
            'message' => 'Détails de la disponibilité récupérés avec succès.',
        ], 200);
    }

    /**
     * Update an availability.
     */
    public function update(Request $request, Availability $availability)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' || $availability->instructor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier cette disponibilité.',
            ], 403);
        }

        $validated = $request->validate([
            'meeting_point_id' => 'sometimes|exists:meeting_points,id',
            'vehicle_id' => 'sometimes|exists:vehicles,id',
            'day_of_week' => 'sometimes|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'date' => 'sometimes|date|after_or_equal:today',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'status' => 'sometimes|boolean',
        ]);

        // Verify that the meeting point and vehicle belong to the instructor
        if (isset($validated['meeting_point_id'])) {
            $meetingPoint = MeetingPoint::where('id', $validated['meeting_point_id'])
                                       ->where('instructor_id', $user->id)
                                       ->first();
            if (!$meetingPoint) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le lieu de rendez-vous spécifié n\'appartient pas à ce moniteur.',
                ], 403);
            }
        }

        if (isset($validated['vehicle_id'])) {
            $vehicle = Vehicle::where('id', $validated['vehicle_id'])
                              ->where('instructor_id', $user->id)
                              ->first();
            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le véhicule spécifié n\'appartient pas à ce moniteur.',
                ], 403);
            }
        }

        // Verify that the day_of_week matches the date
        if (isset($validated['date'])) {
            $dateDayOfWeek = strtolower(\Carbon\Carbon::parse($validated['date'])->locale('fr')->dayName);
            $dayOfWeek = $validated['day_of_week'] ?? $availability->day_of_week;
            if ($dateDayOfWeek !== $dayOfWeek) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le jour de la semaine ne correspond pas à la date spécifiée.',
                ], 422);
            }
        }

        $availability->update(array_filter($validated));

        return response()->json([
            'success' => true,
            'data' => $availability->fresh()->load(['meetingPoint', 'vehicle']),
            'message' => 'Disponibilité mise à jour avec succès.',
        ], 200);
    }

    /**
     * Delete an availability.
     */
    public function destroy(Availability $availability)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor' || $availability->instructor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer cette disponibilité.',
            ], 403);
        }

        $availability->delete();

        return response()->json([
            'success' => true,
            'message' => 'Disponibilité supprimée avec succès.',
        ], 200);
    }

    /**
     * List all availabilities created by the instructor.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $user = Auth::user();

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent voir leurs disponibilités.',
            ], 403);
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $request->date);

        // Calculate the end date (7 days from the start date)
        $endDate = $startDate->copy()->addDays(6);

        $availabilities = Availability::whereBetween('date', [$startDate, $endDate])->where('instructor_id', $user->id)
                ->with(['meetingPoint', 'vehicle'])
                ->get();

        return response()->json([
            'success' => true,
            'data' => $availabilities,
            'message' => 'Liste des disponibilités récupérée avec succès.',
        ], 200);
    }
}