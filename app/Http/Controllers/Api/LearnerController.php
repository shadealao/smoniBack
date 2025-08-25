<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\ListBadge;
use App\Models\Notification;
use App\Models\ModuleStep;
use App\Models\StepModuleItem;
use App\Models\LearnerProgres;
use App\Models\Appointment;
use App\Models\TrainingModule;
use App\Models\Availability;
use App\Models\Subscription;
use App\Models\CodeAccess;
use App\Models\Examen;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class LearnerController extends Controller
{
    /**
     * Badges Users
     */
    public function userBadges()
    {
        $badges = Badge::where('learner_id',auth()->user()->id)->with('list_badge')->get();
        $ids = Badge::where('learner_id',auth()->user()->id)->pluck('list_badge_id')->toArray();

        $nobadges = ListBadge::whereNotIn('id',$ids)->get();

        return response()->json([
            'success' => true,
            'data' => [
                "badges" => $badges,
                "nobadges" => $nobadges,
            ],
            'message' => 'Nombre de badge récupérer avec succès.',
        ], 200);
    }

    /**
     * Nombre de badge obtenu
     */
    public function userBadgesQty() {
        $badges = Badge::where('learner_id',auth()->user()->id)->with('list_badge')->get();

        return response()->json([
            'success' => true,
            'data' =>  count($badges),
            'message' => 'Liste des badges récupérée avec succès.',
        ], 200);
    }

    /**
     * User Progress
     */
    public function userProgress(){

        $myCompetence = LearnerProgres::where('learner_id',auth()->user()->id)->pluck('step_item_id')->toArray();
        
        $trainingModules = TrainingModule::all();

        $module = array();
        $progress = 0;

        foreach ($trainingModules as $trainingModule) {
            
            $subModule = array();
            $total_check_comp = 0;
            $total_comp = 0;
            $moduleSteps = ModuleStep::where('module_id',$trainingModule->id)->get();
            foreach ($moduleSteps as $moduleStep) {
            
                $compet = array();
                $check=0;
                $competences = StepModuleItem::where('step_id',$moduleStep->id)->get();
                foreach ($competences as $competence) {
                    
                    $detail_compet = [
                        'id' => $competence->id,
                        'name' => $competence->description,
                        'is_check' => in_array($competence->id, $myCompetence) ? true : false,
                    ];
                    array_push($compet, $detail_compet );
                    $check = in_array($competence->id, $myCompetence) ? $check+1 : $check;
                }

                $detail_subModule = [
                    'id' => $moduleStep->id,
                    'code' => $moduleStep->code,
                    'name' => $moduleStep->name,
                    'stat' => ($check * 100)/$competences->count(),
                    'pdf' => asset($moduleStep->pdf),
                    'competence' => $compet,
                ];
                
                array_push($subModule, $detail_subModule );
                $total_check_comp = $total_check_comp + $check;
                $total_comp = $total_comp + $competences->count();
            }

            $detail_module = [
                'id' => $trainingModule->id,
                'code' => $trainingModule->code,
                'name' => $trainingModule->name,
                'stat' => ($total_check_comp * 100)/($total_comp != 0 ? $total_comp : 1),
                'subModule' => $subModule,
            ];
            array_push($module, $detail_module );
            $progress = $progress + $detail_module['stat'];

        }

        return response()->json([
            'success' => true,
            'progress' =>$progress/count($trainingModules),
            'data' => $module,
        ], 200); 
        
    }

    /**
     * Lesson
     */
    public function lessonLearner(Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['all','scheduled', 'confirmed', 'completed', 'cancelled','pending','notation'])],
        ]);
        if($validated['status'] == 'all') {
            $lessons = Appointment::where('learner_id', auth()->user()->id)->with(['instructor', 'availability.meetingPoint', 'vehicle'])->orderBy('date','asc')->paginate(10);
        } else {
            $lessons = Appointment::where('learner_id', auth()->user()->id)
                ->where('status', $validated['status'])
                ->with(['instructor', 'availability.meetingPoint', 'vehicle'])
                ->orderBy('date','asc')
                ->paginate(10);
        }

        return response()->json([
            'success' => true,
            'data' => $lessons,
        ], 200);
    }

    /**
     * Annuler un rendez-vous
     */
    public function cancelrRdv(Request $request) {
        
        $validated = $request->validate([
            'learner_id' => ['required', 'integer', 'exists:users,id'],
            'appointment_id' => ['required', 'integer', 'exists:appointments,id'],
            'cancellation_reason' => ['required', 'string']
        ]);

        // Vérifier si le rendez-vous existe
        $appointment = Appointment::where('id', $validated['appointment_id'])
            ->where('learner_id', $validated['learner_id'])
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'Rendez-vous introuvable.'
            ], 404);
        }

        // Vérifier la différence de temps
        $now = now();
        $rdvDate = $appointment->date instanceof Carbon ? $appointment->date : Carbon::parse($appointment->date);
        $diffInHours = $now->diffInHours($rdvDate, false);
        if ($diffInHours < 48) {
            return response()->json([
                'success' => false,
                'message' => 'Annulation impossible : moins de 48h avant le rendez-vous.'
            ], 403);
        }

        // Annulation possible
        $appointment->status = 'cancelled';
        $appointment->cancellation_reason = $validated['cancellation_reason'];
        $appointment->save();

        $availability = $appointment->availability;
        $vehicle = $availability->vehicle;

        // Vérifier la souscription de l'apprenant avec le même gearbox
        $subscriptions = Subscription::where('learner_id', $validated['learner_id'])
            ->where('gearbox', $vehicle->gearbox_type)
            ->where('status', 'active')
            ->orderBy('created_at','desc')
            ->first();
        
        if($subscriptions) {
            $subscriptions->hour+= 1;
            $subscriptions->save();
        }
        

        return response()->json([
            'success' => true,
            'message' => 'Rendez-vous annulé avec succès.'
        ], 200);
    }

    /**
     * Liste les moniteurs disponibles à une date, avec un type de boîte de vitesses et éventuellement un point de rendez-vous.
     * Retourne pour chaque moniteur : ses disponibilités non reliées à un rendez-vous (appointment), et le véhicule associé.
     */
    public function instructorsAvailable(Request $request)
    {
        $validated = $request->validate([
            'datesearch' => 'required|date',
            'gearbox' => 'required|in:all,automatic,manual',
            'meeting_point' => 'nullable|string',
        ]);

        // MySearch
        if($request->gearbox == "all"){
            
            $query = Availability::where('date', $validated['datesearch'])
            ->whereHas('vehicle', function($q) use ($validated) {
            })->whereHas('instructor', function($q) {
                $q->where('is_active', true);
            })
            ->whereDoesntHave('appointment');
        }else{
            $query = Availability::where('date', $validated['datesearch'])
            ->whereHas('vehicle', function($q) use ($validated) {
                $q->where('gearbox_type', $validated['gearbox']);
            })->whereHas('instructor', function($q) {
                $q->where('is_active', true);
            })
            ->whereDoesntHave('appointment');
        }
       
        if (!empty($validated['meeting_point'])) {
            $query->whereHas('meetingPoint', function($q) use ($validated) {
                $q->where('label', 'like', '%' . $validated['meeting_point'] . '%');
            });
        }

        $availabilities = $query->with(['vehicle', 'meetingPoint'])->orderBy('date','desc')->orderBy('start_time','asc')->get();

        // Grouper par moniteur
        $result = $availabilities->groupBy('instructor_id')->map(function($items) {
            $instructor = $items->first()->instructor;
            if($instructor->is_active == true) {
               return [
                    'instructor' => $instructor,
                    'instructor_profile' => $instructor->instructorProfile,
                    'availabilities' => $items->values(),
                ];
            }
        })->values();


        // Others
        if($request->gearbox == "all"){
            
            $a = Availability::where('date', $validated['datesearch'])
            ->whereHas('vehicle', function($q) use ($validated) {
            })
            ->whereDoesntHave('appointment');
        }else{
            $a = Availability::where('date', $validated['datesearch'])
            ->whereHas('vehicle', function($q) use ($validated) {
                $q->where('gearbox_type', $validated['gearbox']);
            })
            ->whereDoesntHave('appointment');
        }

        $a->whereHas('meetingPoint', function($q) use ($validated) {
                $q->where('label', 'like', '%%');
            })->whereHas('instructor', function($q) {
                $q->where('is_active', true);
            });

        $b = $a->with(['vehicle', 'meetingPoint'])->orderBy('date','desc')->orderBy('start_time','asc')->get();

        // Grouper par moniteur
        $others = $b->groupBy('instructor_id')->map(function($items) {
            $instructor = $items->first()->instructor;
            return [
                'instructor' => $instructor,
                'instructor_profile' => $instructor->instructorProfile,
                'availabilities' => $items->values(),
            ];
            
        })->values();

        return response()->json([
            'success' => true,
            'data' => $result,
            'others' => $others,
            'message' => 'Liste des moniteurs et disponibilités sans rendez-vous.',
        ], 200);
    }

    /**
     * List examen to learner
     *
     */
    public function ListExamRdv(Request $request, $learner_id) {
        $examens = Examen::where('learner_id', $learner_id)
                            ->with(['learner', 'monitor'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $examens,
            'message' => "List Examen"
        ], 200);
    }

    /**
     * Accès au service de code.
     * 
     */
    public function learnercodeaccess(Request $request, $learner_id) {
        $learner = User::where('id', $learner_id)->first();

        if(!$learner) {
            return response()->json([
                'success' => false,
                'message' => "Apprenant inexistant",
            ], 404);
        }

        $subscriptions = Subscription::where('learner_id', $learner_id)->where('status','active')->where('type_service','Pack code')->get();

        if($subscriptions) {

            $access = CodeAccess::get();

            return response()->json([
                'success' => false,
                'data' => $access,
                'message' => "Abonnement disponible",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Pas d'abonnement de code pour cet utilisateur",
            ], 404);
        }

    }

}
