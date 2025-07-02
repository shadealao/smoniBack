<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstructorProfile;
use App\Models\Vehicle;
use App\Models\MeetingPoint;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\UserDoc;
use App\Models\AvailabilityRepeated;
use App\Http\Resources\AppointmentLearnerResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * @tags Zone Monitor (Admin)
 */

class MonitorController extends Controller
{
    /**
     * List
     */
    public function index(Request $request){
        $q = $request->q ? : '';
        $users = User::where('role','instructor')      
            ->where(function ($query) use ($q) {
                $query->where(DB::raw('lower(lastname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(firstname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(email)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(phone)'),'like','%'.strtolower($q).'%');
            })->with('instructorProfile')->orderBy('created_at','desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);
    }
    
    /**
     * Show Instructor
     */
    public function show(User $user)
    {
        $vehicles = Vehicle::where('instructor_id', $user->id)->get();
        $meetingPoints = MeetingPoint::where('instructor_id', $user->id)->where('is_active',true)->get();
        $repeateds = array();
        $days = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
        foreach ($days as $day) {
            $repeated = AvailabilityRepeated::where('monitor_id', $user->id)->where('day_of_week', $day)->get();
            $info = [
                $day => $repeated
            ];
            array_push($repeateds,$info);
        }

        $perso = User::where('id',$user->id)->with('instructorProfile')->first();
        $userDocs = UserDoc::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'user' => $perso,
            'doc' => $userDocs,
            'vehicles' => $vehicles,
            'meetingPoints' => $meetingPoints,
            'repeateds' => $repeateds,
            'message' => 'Liste des véhicules récupérée avec succès.',
        ], 200);
    }

    /**
     * Action (Active/Lock) 
     */
    public function action(User $user){
        
        $user->update([
            'is_active' => $user->is_active ? false : true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $user->is_active ? 'Compte Activé' : 'Compte Bloqué',
        ], 200);
    }

    /**
     * List all vehicles by the instructor.
     */
    public function listVehicles(User $user)
    {
        $vehicles = Vehicle::where('instructor_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'data' => $vehicles,
            'message' => 'Liste des véhicules récupérée avec succès.',
        ], 200);
    }

    /**
     * List all meeting points by the instructor.
     */
    public function listMeetingPoint(User $user)
    {

        $meetingPoints = MeetingPoint::where('instructor_id', $user->id)->where('is_active',true)->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $meetingPoints,
            'message' => 'Liste des lieux de rendez-vous récupérée avec succès.',
        ], 200);
    }

    /**
     * List all availabilities by the instructor.
     */
    public function listAvailabilities(Request $request, User $user)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $startDate = Carbon::createFromFormat('Y-m-d', $request->date)->toDateString();

        // Calculate the end date (7 days from the start date)
        $endDate = Carbon::createFromFormat('Y-m-d', $request->date)->addDays(6)->toDateString();

        // Force the date format to 'Y-m-d' before the whereBetween query
        // $startDate = $startDate->format('Y-m-d');
        // $endDate = $endDate->format('Y-m-d');

        $availabilities = Availability::whereBetween('date', [$startDate, $endDate])
            ->where('instructor_id', $user->id)
            ->with(['meetingPoint', 'vehicle', 'appointment.learner'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availabilities,
            'message' => 'Liste des disponibilités récupérée avec succès.',
        ], 200);
    }

    /**
     * List all availabilities Repeat by the instructor.
     * Affiche la liste des disponibilités récurrentes du moniteur connecté.
     */
    public function listAvailabilitiesRepeat(User $user)
    {
        $repeateds = array();
        $days = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];
        foreach ($days as $day) {
            $repeated = AvailabilityRepeated::where('monitor_id', $user->id)->where('day_of_week', $day)->get();
            $info = [
                $day => $repeated
            ];
            array_push($repeateds,$info);
        }
        return response()->json(['success' => true, 'data' => $repeateds]);
    }

    /**
     * List Learner By Instrutor
     */
    public function listLearner(Request $request, User $user)
    {
        
        $validated = $request->validate([
            'per_page' => 'nullable|integer',
        ]);
        $per_page = $request->per_page ?? 200;

        $learners = Appointment::query()
            ->select('learner_id')
            ->selectRaw('SUM(CASE WHEN status = \'completed\' THEN duration ELSE 0 END) as total_duration')
            ->where('instructor_id', $user->id)
            ->whereIn('status', ['pending', 'notation', 'completed'])
            ->groupBy('learner_id')
            ->paginate($per_page);

        return AppointmentLearnerResource::collection($learners);
    }

    /**
     * LIst Apointment
     */
    public function listAppointment(Request $request, User $user)
    {

        $validated = $request->validate([
            'per_page' => 'nullable|integer',
        ]);
        $per_page = $request->per_page ?? 200;

        $appointments = Appointment::where('instructor_id', $user->id)->with('learner')->orderBy('created_at','desc')->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $appointments,
        ], 200);
    }

    /** 
     * Create Appointment
     * 
     */
    public function createAppointment(Request $request, User $user)
    {
        $validated = $request->validate([
            'meeting_point_id' => 'required|exists:meeting_points,id',
            'learner_id' => 'required|exists:users,id',
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

        DB::beginTransaction();
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
    
}
