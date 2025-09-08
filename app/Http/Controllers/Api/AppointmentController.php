<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Note;
use App\Models\Vehicle;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Resources\AppointmentLearnerResource;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Book an appointment (learner only).
     */
    public function store(Request $request){
        $user = Auth::user();

        if ($user->role !== 'learner') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les apprenants peuvent réserver des rendez-vous.',
            ], 403);
        }


        // Vérifie si abonnement en cours et heure disponible
        $subscriptions = Subscription::where('learner_id', $user->id)->where('status','active')->where('type_service','Conduite')->with(['service.items','learner'])->get();

        // Si pas d'abonnement
        if(!$subscriptions || !count($subscriptions)) {
            return response()->json([
                'success' => false,
                'etat' => 'notSubscription',
                'message' => 'Vous n\'avez aucun abonnement de conduite disponible ou elles ont expiré',
            ], 403);
        }

        // Si abonnement et pas d'heure
        if($subscriptions) {
            $hasAvailableHours = false;
            foreach ($subscriptions as $subscription) {
                if (isset($subscription->hour) && $subscription->hour > 1) {
                    $hasAvailableHours = true;
                    break;
                }
            }

            if (!$hasAvailableHours) {
                return response()->json([
                    'success' => false,
                    'etat' => 'notSubscription',
                    'message' => 'Vous n\'avez aucune heure disponible pour vos abonnements de conduite',
                ], 403);
            }
        }

        $validated = $request->validate([
            'availability_id' => 'required|exists:availabilities,id',
            'price' => 'nullable|numeric|min:0',
            'tag' => 'nullable|string|max:100',
        ]);

        $availability = Availability::findOrFail($validated['availability_id']);

        // Check if availability is active and in the future
        if (!$availability->status || Carbon::parse($availability->date)->isPast()) {
            return response()->json([
                'success' => false,
                'etat' => 'notAvailble',
                'message' => 'Cette disponibilité n\'est pas disponible.',
            ], 422);
        }

        // Check if the availability is already booked
        if (Appointment::where('availability_id', $availability->id)->exists()) {
            return response()->json([
                'success' => false,
                'etat' => 'notAvailble',
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
                'etat' => 'notAvailble',
                'message' => 'Le véhicule spécifié n\'appartient pas à ce moniteur.',
            ], 422);
        }

        // Si la boite (gearbox) de l'abonnement ne corresponds pas aux véhicule de la disponibilité choisi ou si il n'a pas d'heure restant pour l'abonnement ayant cette boite
        if ($subscriptions) {
            $validSubscription = null;
            foreach ($subscriptions as $subscription) {
                // Supposons que l'abonnement a un champ 'gearbox' et 'hour'
                if (
                    isset($subscription->gearbox, $subscription->hour) &&
                    $subscription->gearbox === $availability->vehicle->gearbox_type &&
                    $subscription->hour > 0
                ) {
                    $validSubscription = $subscription;
                    break;
                }
            }

            if (!$validSubscription) {
                // Aucun abonnement compatible (soit pas la bonne boîte, soit pas d'heure)
                return response()->json([
                    'success' => false,
                    'etat' => 'notSubscription',
                    'message' => "Abonnement incompatible avec cette boîte ou heures restantes insuffisantes."
                ], 403);
            } else {
                $validSubscription->hour-=1;
                $validSubscription->save();
            }
        }

        $appointment = Appointment::create([
            'learner_id' => $user->id,
            'instructor_id' => $availability->instructor_id,
            'availability_id' => $availability->id,
            'vehicle_id' => $availability->vehicle_id,
            'date' => $availability->date,
            'start_time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'duration' => Carbon::parse($availability->start_time)->diffInMinutes($availability->end_time),
            'status' => 'scheduled',
            'price' => 0,
            'tag' => "rdv",
            'lesson_notes' => null,
            'presence_student' => false,
            'presence_monitor' => false,
            'finished' => false,
        ]);

        $this->sendmailer( $user->id, 'Reservation pour Rendez-vous', "Rendez-vous pour cours de conduite", 'Vous venez de faire une résevation pour un cours qui aura lieu '.$availability->date.' de '.$availability->start_time.' à '.$availability->end_time, 'appointment');

        $this->sendmailer( $availability->instructor_id, 'Reservation pour Rendez-vous', "Rendez-vous pour cours de conduite", 'Une résevation pour un cours qui aura lieu '.$availability->date.' de '.$availability->start_time.' à '.$availability->end_time.' par '.$user->lastname, 'appointment');

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
        
        $this->sendmailer( $user->id, 'Reservation pour Rendez-vous', "Rendez-vous pour cours de conduite", 'Vous venez de faire une résevation pour un cours qui aura lieu '.$newAvailability->date.' de '.$newAvailability->start_time.' à '.$newAvailability->end_time, 'appointment');

        return response()->json([
            'success' => true,
            'data' => $appointment->fresh()->load(['availability', 'vehicle', 'instructor']),
            'message' => 'Rendez-vous mis à jour avec succès.',
        ], 200);
    }

    /**
     * Confirme an appointment. (Only Instructor)
     */
    public function confirme(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent confirmer des rendez-vous.',
            ], 403);
        }

        // Prevent canceling already canceled or completed appointments
        if ($appointment->status === 'cancelled' || $appointment->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ce rendez-vous est déjà annulé ou terminé.',
            ], 422);
        }

        $appointment->update([
            'status' => 'confirmed',
        ]);

        
        $this->sendmailer( $user->id, 'Confirmation Rendez-vous', 'Confirmation Rendez-vous', 'Vous venez de confirmer une résevation pour un cours qui aura lieu '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time, 'appointment');

        $this->sendmailer( $appointment->learner_id, 'Confirmation Rendez-vous', 'Confirmation Rendez-vous', 'Votre moniteur vient de confirmer votre résevation pour un cours qui aura lieu '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time, 'appointment');

        return response()->json([
            'success' => true,
            'data' => $appointment,
            'learner' => $appointment->learner,
            'message' => 'Rendez-vous confirmé avec succès.',
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


        $date = new \DateTime($appointment->date);
        $date->setTime(date('H', strtotime($appointment->start_time)),0,0);
        $date->format('Y-m-d H:i:s');
        $appointmentDateTime = Carbon::parse($date);
        
        // $date1 = new DateTime();
        // $date2 = new DateTime($appointment->date . ' ' . $appointment->start_time);
        // $interval = $date1->diff($date2);

        // Prevent canceling already canceled or completed appointments
        if ($appointment->status === 'cancelled' || $appointment->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ce rendez-vous est déjà annulé ou terminé.',
            ], 422);
        }

        // Learners can only cancel 48 hours before
        if ($user->role === 'learner') {
            if (abs($appointmentDateTime->diffInHours(Carbon::now(), false)) < 48) {
                return response()->json([
                    'success' => false,
                    'message' => 'Les apprenants ne peuvent annuler que 48 heures avant le rendez-vous.',
                ], 403);
            }
        }
        
        $availability = $appointment->availability;
        $vehicle = $availability->vehicle;

        // Vérifier la souscription de l'apprenant avec le même gearbox
        $subscriptions = Subscription::where('learner_id', $appointment->learner_id)
            ->where('gearbox', $vehicle->gearbox_type)
            ->where('status', 'active')
            ->orderBy('created_at','desc')
            ->first();
        
        if($subscriptions) {
            $subscriptions->hour+= 1;
            $subscriptions->save();
        }
        
        $this->sendmailer( $user->id, 'Annullation Rendez-vous', 'Annullation Rendez-vous', 'Vous venez d\'annuler une résevation pour un cours qui aura lieu '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time, 'appointment');

        $this->sendmailer( $appointment->learner_id, 'Annullation Rendez-vous', 'Annullation Rendez-vous', 'Votre moniteur vient d\'annuler votre résevation pour un cours qui aura lieu '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time.'<br> <b>Raison</b>: '.$validated['cancellation_reason'], 'appointment');


        if ($user->role === 'instructor') {
            if (abs($appointmentDateTime->diffInHours(Carbon::now(), false)) < 48) {

                $appointment->update([
                    'status' => 'cancelled',
                    'cancellation_reason' => $validated['cancellation_reason'],
                    'canceled_by_monitor' => $user->role === 'learner' ? false : true
                ]);
            }else{
                $appointment->delete();
            }
        }

        if ($user->role === 'learner') {
            $appointment->delete();
        }
        
        return response()->json([
            'success' => true,
            'data' => 'ok',
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

        $appointment->presence_student = true;
        $appointment->presence_monitor = true;
        $appointment->status = 'pending';
        $appointment->save();

        
        $this->sendmailer( $user->id, 'Comfirmation de présence au rendez-vous', 'Comfirmation de présence au rendez-vous', 'Vous venez de confirmer la présence de l\'apprenant '.$appointment->learner->lastname.' au rendez-vous '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time, 'appointment');

        $this->sendmailer( $appointment->learner_id, 'Comfirmation de présence au rendez-vous', 'Comfirmation de présence au rendez-vous', 'Votre moniteur vient de confirmer votre présence au cours', 'appointment');

        return response()->json([
            'success' => true,
            'data' => $appointment->fresh(),
            'message' => 'Présence marquée avec succès.',
        ], 200);
    }

    /**
     * Mark Absence for an appointment.
     */
    public function markAbsence(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'reason' => 'nullable|string',
        ]);

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

        $appointment->status = 'completed';
        $appointment->reason = $request->reason;

        $appointment->save();

        $this->sendmailer( $user->id, 'Absence au rendez-vous', 'Absence au rendez-vous', 'Vous venez de marquer l\'absence de l\'apprenant '.$appointment->learner->lastname.' au rendez-vous '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time, 'appointment');

        $this->sendmailer( $appointment->learner_id, 'Absence au rendez-vous', 'Absence au rendez-vous', 'Vous avez été noté absent par votre moniteur au cours du '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time, 'appointment');

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
        $appointment->status = 'notation';
        $appointment->save();

        $this->sendmailer( $appointment->learner_id, 'Fin du cours', 'FIn du cours', 'Votre moniteur vient de marquer la fin de votre cours. Merci', 'appointment');


        return response()->json([
            'success' => true,
            'data' => $appointment->fresh(),
            'message' => 'Rendez-vous marqué comme terminé avec succès.',
        ], 200);
    }

    /**
     * LIst Learner By Instrutor
     */
    public function listLearner(Request $request, User $user)
    {
        
        $validated = $request->validate([
            'per_page' => 'nullable|integer',
        ]);
        $per_page = $request->per_page ?? 200;

        if($user->role != 'instructor')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un instructeur',
            ], 403);

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
    public function lists(Request $request)
    {

        $validated = $request->validate([
            'per_page' => 'nullable|integer',
            'status' => 'in:all,scheduled,confirmed,completed,cancelled,pending,notation',
        ]);
        $per_page = $request->per_page ?? 20;

        if(auth()->user()->role != 'instructor')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un instructeur',
            ], 403);

        if($request->status != 'all') {
            $appointments = Appointment::where('instructor_id', auth()->user()->id)
                ->where('status', $request->status)
                ->with('learner')
                ->orderBy('date','desc')
                ->paginate($per_page);
        } else {
            $appointments = Appointment::where('instructor_id', auth()->user()->id)
                ->with('learner')
                ->orderBy('date','desc')
                ->paginate($per_page);
        }

        return response()->json([
            'success' => true,
            'data' => $appointments,
        ], 200);
    }

    /**
     * Lesson by Learner
     */
    public function lessonLearner(Request $request, User $user)
    {
        if($user->role != 'learner')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un apprenant',
            ], 403);

        $lessons = Appointment::where('learner_id', $user->id)->with('instructor')->orderBy('created_at','desc')->get();

        return response()->json([
            'success' => true,
            'data' => $lessons,
        ], 200);
    }

    /**
     * Comments by Learner
     */
    public function comments(Request $request, User $user)
    {
        $validated = $request->validate([
            'per_page' => 'integer|nullable',
        ]);
        $per_page = $request->per_page ?? 10;
        if(auth()->user()->role != 'instructor')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un moniteur',
            ], 403);

        $lessons = Note::where('student_id', $user->id)->with('monitor')->orderBy('created_at','desc')->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $lessons,
        ], 200);
    }

    /**
     * Add Comment.
     */
    public function addComment(Request $request)
    {
        $user = Auth::user();

        if(auth()->user()->role != 'instructor')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un moniteur',
            ], 403);

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'comment' => 'required|string',
        ]);

        $note = Note::create([
            'student_id' => $request->student_id,
            'monitor_id' => Auth::user()->id,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commentaire Pédagogique bien envoyé.',
        ], 201);
    }

    /**
     * Update Comment.
     */
    public function updateComment(Request $request, Note $note)
    {
        $user = Auth::user();

        if(auth()->user()->role != 'instructor')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un moniteur',
            ], 403);

        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        $note->update([
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Commentaire Pédagogique bien modifié.',
        ], 201);
    }

    public function cancelOldAppointment(){
        $appointments = Appointment::where('status','scheduled')->with('learner','instructor')->orderBy('date','desc')->get();

        foreach ($appointments as $appointment) {
            $now = new \DateTime();
            $date = new \DateTime($appointment->date);

            if($date < $now){
                $availability = $appointment->availability;
                $vehicle = $availability->vehicle;

                $subscriptions = Subscription::where('learner_id', $appointment->learner_id)
                    ->where('gearbox', $vehicle->gearbox_type)
                    ->where('status', 'active')
                    ->orderBy('created_at','desc')
                    ->first();
                
                if($subscriptions) {
                    $subscriptions->hour+= 1;
                    $subscriptions->save();
                }
                $this->sendmailer( $appointment->instructor_id, 'Annullation Rendez-vous', 'Annullation Rendez-vous', 'La résevation pour un cours qui aura lieu '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time.' a été annulé à cause du delais passer', 'appointment');

                $this->sendmailer( $appointment->learner_id, 'Annullation Rendez-vous', 'Annullation Rendez-vous', 'La résevation pour un cours qui aura lieu '.$appointment->date.' de '.$appointment->start_time.' à '.$appointment->end_time.' a été annulé à cause du delais passer', 'appointment');

                $appointment->update([
                    // 'availability_id' => null,
                    'status' => 'cancelled',
                    'cancellation_reason' => "Délai d'attente passé",
                    'canceled_by_monitor' => true
                ]);
            }
        }
    }
}