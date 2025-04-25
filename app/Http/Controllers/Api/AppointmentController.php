<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Book an appointment (learner only).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'learner') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les apprenants peuvent réserver des rendez-vous.',
            ], 403);
        }

        $validated = $request->validate([
            'availability_id' => 'required|exists:availabilities,id',
            'price' => 'required|numeric|min:0',
            'tag' => 'nullable|string|max:100',
        ]);

        $availability = Availability::findOrFail($validated['availability_id']);

        // Check if availability is active and in the future
        if (!$availability->status || Carbon::parse($availability->date)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette disponibilité n\'est pas disponible.',
            ], 422);
        }

        // Check if the availability is already booked
        if (Appointment::where('availability_id', $availability->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette disponibilité est déjà réservée.',
            ], 422);
        }

        // Verify vehicle belongs to the instructor
        $vehicle = Vehicle::where('id', $availability->vehicle_id)
                          ->where('instructor_id', $availability->instructor_id)
                          ->first();
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Le véhicule spécifié n\'appartient pas à ce moniteur.',
            ], 422);
        }

        $appointment = Appointment::create([
            'learner_id' => $user->id,
            'instructor_id' => $availability->instructor_id,
            'availability_id' => $availability->id,
            'vehicle_id' => $availability->vehicle_id,
            'date' => $availability->date,
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'duration' => Carbon::parse($availability->end_time)->diffInMinutes($availability->start_time),
            'status' => 'scheduled',
            'price' => $validated['price'],
            'tag' => $validated['tag'],
            'lesson_notes' => null,
            'presence_student' => false,
            'presence_monitor' => false,
            'finished' => false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $appointment->load(['availability', 'vehicle', 'instructor']),
            'message' => 'Rendez-vous réservé avec succès.',
        ], 201);
    }

    /**
     * Modify an appointment (date/time or lesson notes).
     */
    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Only learner or instructor of the appointment can modify
        if (
            ($user->role === 'learner' && $appointment->learner_id !== $user->id) ||
            ($user->role === 'instructor' && $appointment->instructor_id !== $user->id) ||
            ($user->role !== 'learner' && $user->role !== 'instructor')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce rendez-vous.',
            ], 403);
        }

        $validated = $request->validate([
            'availability_id' => 'sometimes|exists:availabilities,id',
            'lesson_notes' => 'sometimes|nullable|array',
            'price' => 'sometimes|numeric|min:0',
            'tag' => 'sometimes|nullable|string|max:100',
        ]);

        // If updating date/time via new availability
        if (isset($validated['availability_id'])) {
            if ($user->role !== 'instructor') {
                return response()->json([
                    'success' => false,
                    'message' => 'Seuls les moniteurs peuvent modifier la date/heure.',
                ], 403);
            }

            $newAvailability = Availability::findOrFail($validated['availability_id']);

            // Check if new availability is active and in the future
            if (!$newAvailability->status || Carbon::parse($newAvailability->date)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La nouvelle disponibilité n\'est pas disponible.',
                ], 422);
            }

            // Check if new availability is already booked
            if (Appointment::where('availability_id', $newAvailability->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La nouvelle disponibilité est déjà réservée.',
                ], 422);
            }

            // Verify new availability belongs to the same instructor
            if ($newAvailability->instructor_id !== $appointment->instructor_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La disponibilité ne correspond pas au moniteur.',
                ], 422);
            }

            // Update date/time fields
            $appointment->availability_id = $newAvailability->id;
            $appointment->vehicle_id = $newAvailability->vehicle_id;
            $appointment->date = $newAvailability->date;
            $appointment->start_time = $newAvailability->start_time;
            $appointment->end_time = $newAvailability->end_time;
            $appointment->duration = Carbon::parse($newAvailability->end_time)->diffInMinutes($newAvailability->start_time);
        }

        // Update other fields if provided
        if (isset($validated['lesson_notes'])) {
            $appointment->lesson_notes = $validated['lesson_notes'];
        }
        if (isset($validated['price'])) {
            $appointment->price = $validated['price'];
        }
        if (isset($validated['tag'])) {
            $appointment->tag = $validated['tag'];
        }

        $appointment->save();

        return response()->json([
            'success' => true,
            'data' => $appointment->fresh()->load(['availability', 'vehicle', 'instructor']),
            'message' => 'Rendez-vous mis à jour avec succès.',
        ], 200);
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Only learner or instructor of the appointment can cancel
        if (
            ($user->role === 'learner' && $appointment->learner_id !== $user->id) ||
            ($user->role === 'instructor' && $appointment->instructor_id !== $user->id) ||
            ($user->role !== 'learner' && $user->role !== 'instructor')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à annuler ce rendez-vous.',
            ], 403);
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        // Learners can only cancel 24 hours before
        if ($user->role === 'learner') {
            $appointmentDateTime = Carbon::parse($appointment->date . ' ' . $appointment->start_time);
            if ($appointmentDateTime->diffInHours(Carbon::now(), false) < 24) {
                return response()->json([
                    'success' => false,
                    'message' => 'Les apprenants ne peuvent annuler que 24 heures avant le rendez-vous.',
                ], 403);
            }
        }

        // Prevent canceling already canceled or completed appointments
        if ($appointment->status === 'cancelled' || $appointment->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ce rendez-vous est déjà annulé ou terminé.',
            ], 422);
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $appointment->fresh(),
            'message' => 'Rendez-vous annulé avec succès.',
        ], 200);
    }

    /**
     * Mark presence for an appointment.
     */
    public function markPresence(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Only learner or instructor of the appointment can mark presence
        if (
            ($user->role === 'learner' && $appointment->learner_id !== $user->id) ||
            ($user->role === 'instructor' && $appointment->instructor_id !== $user->id) ||
            ($user->role !== 'learner' && $user->role !== 'instructor')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à marquer la présence pour ce rendez-vous.',
            ], 403);
        }

        // Prevent marking presence for canceled or completed appointments
        if ($appointment->status === 'cancelled' || $appointment->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de marquer la présence pour un rendez-vous annulé ou terminé.',
            ], 422);
        }

        if ($user->role === 'learner') {
            $appointment->presence_student = true;
        } else {
            $appointment->presence_monitor = true;
        }

        // Update status to confirmed if both are present
        if ($appointment->presence_student && $appointment->presence_monitor) {
            $appointment->status = 'confirmed';
        }

        $appointment->save();

        return response()->json([
            'success' => true,
            'data' => $appointment->fresh(),
            'message' => 'Présence marquée avec succès.',
        ], 200);
    }

    /**
     * Mark an appointment as finished.
     */
    public function markFinished(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Only learner or instructor of the appointment can mark as finished
        if (
            ($user->role === 'learner' && $appointment->learner_id !== $user->id) ||
            ($user->role === 'instructor' && $appointment->instructor_id !== $user->id) ||
            ($user->role !== 'learner' && $user->role !== 'instructor')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à marquer ce rendez-vous comme terminé.',
            ], 403);
        }

        // Prevent marking as finished for canceled or already finished appointments
        if ($appointment->status === 'cancelled' || $appointment->finished) {
            return response()->json([
                'success' => false,
                'message' => 'Ce rendez-vous est déjà annulé ou terminé.',
            ], 422);
        }

        // Require both presences before marking as finished
        if (!$appointment->presence_student || !$appointment->presence_monitor) {
            return response()->json([
                'success' => false,
                'message' => 'Les deux parties doivent confirmer leur présence avant de marquer le rendez-vous comme terminé.',
            ], 422);
        }

        $appointment->finished = true;
        $appointment->status = 'completed';
        $appointment->save();

        return response()->json([
            'success' => true,
            'data' => $appointment->fresh(),
            'message' => 'Rendez-vous marqué comme terminé avec succès.',
        ], 200);
    }
}