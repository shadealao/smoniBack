<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * LIst Apointment
     */
    public function index(Request $request)
    {

        $validated = $request->validate([
            'per_page' => 'nullable|integer',
        ]);
        $per_page = $request->per_page ?? 20;

        if(auth()->user()->role != 'instructor')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un instructeur',
            ], 403);

        $appointments = Appointment::with(['learner','instructor'])->orderBy('created_at','desc')->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $appointments,
        ], 200);
    }

    /** 
     * Create Appointment
     * 
     */
    public function createAppointment(Request $request)
    {
        $validated = $request->validate([
            'meeting_point_id' => 'required|exists:meeting_points,id',
            'learner_id' => 'required|exists:users,id',
            'instructor_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'day_of_week' => 'required|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'sometimes|boolean',
        ]);

        // Verify that the meeting point and vehicle belong to the instructor
        $meetingPoint = MeetingPoint::where('id', $validated['meeting_point_id'])
                                   ->where('instructor_id', $request->instructor_id)
                                   ->first();
        $vehicle = Vehicle::where('id', $validated['vehicle_id'])
                          ->where('instructor_id', $request->instructor_id)
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

        DB::beginTransaction();
            $availability = Availability::create([
                'instructor_id' => $request->instructor_id,
                'meeting_point_id' => $validated['meeting_point_id'],
                'vehicle_id' => $validated['vehicle_id'],
                'day_of_week' => $validated['day_of_week'],
                'date' => $validated['date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'status' => $validated['status'] ?? true,
            ]);

            $appointment = Appointment::create([
                'learner_id' => $request->learner_id,
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
        
        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $appointment,
            'message' => 'Disponibilité créée avec succès.',
        ], 201);
    }

    /**
     * Delete Appointment
     */
}
