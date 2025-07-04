<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\Contract;
use App\Models\Subscription;
use App\Models\ListBadge;
use App\Models\Notification;
use App\Models\ModuleStep;
use App\Models\StepModuleItem;
use App\Models\LearnerProgres;
use App\Models\Appointment;
use App\Models\TrainingModule;
use App\Models\Availability;
use App\Models\User;
use App\Models\Examen;
use Illuminate\Support\Facades\DB;
/**
 * @tags Zone Learner (Admin)
 */
class LearnerController extends Controller
{
    /**
     * List
     */
    public function index(Request $request){
        $q = $request->q ? : '';
        $users = User::where('role','learner')      
            ->where(function ($query) use ($q) {
                $query->where(DB::raw('lower(lastname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(firstname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(email)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(phone)'),'like','%'.strtolower($q).'%');
            })->with('learnerProfile')->orderBy('created_at','desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);
    }

    /**
     * show
     */
    public function show(User $user){
        $perso = User::where('id',$user->id)->with('learnerProfile')->first();

        return response()->json([
            'success' => true,
            'data' => $perso,
        ], 200);
    }

    /**
     * Action (Active/Lock) 
     */
    public function action(Request $request, User $user){
        
        $user->update([
            'is_active' => $user->is_active ? false : true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $user->is_active ? 'Compte Activé' : 'Compte Bloqué',
        ], 200);
    }

    /**
     * Badges 
     */
    public function userBadges(User $user)
    {
        $badges = Badge::where('learner_id',$user->id)->with('list_badge')->get();
        $ids = Badge::where('learner_id',$user->id)->pluck('id')->toArray();

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
     * Progression
     */
    public function userProgress(User $user){

        $myCompetence = LearnerProgres::where('learner_id',$user->id)->pluck('step_item_id')->toArray();
        
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
                    'stat' => ($check * 100)/$moduleStep->competences->count(),
                    'competence' => $compet,
                ];
                array_push($subModule, $detail_subModule );
                $total_check_comp = $total_check_comp + $check;
                $total_comp = $total_comp + $moduleStep->competences->count();
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
            'progress' =>$progress,
            'data' => $module,
        ], 200); 
        
    }

    /**
     * Appointment by Learner
     */
    public function lessonLearner(Request $request, User $user)
    {
        $lessons = Appointment::where('learner_id', $user->id)->with('instructor')->orderBy('created_at','desc')->get();

        return response()->json([
            'success' => true,
            'data' => $lessons,
        ], 200);
    }

    /** 
     * List subsription by Learner
     * 
    */
    public function mySubscribe(User $user){
        
        $subscriptions = Subscription::where('learner_id', $user->id)->with(['service.items','learner']) ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $subscriptions,
        ], 200);
    }

    /**
     * Add contract for Subcription
     * 
     * @requestMediaType multipart/form-data
     */
    public function addcontract(Request $request, User $user){

        $validated = $request->validate([
            'subscription_id' => 'required|integer',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB max
        ]);

        $exist = Contract::where('subscription_id',$request->subscription_id)->first();
        if($exist)
            return response()->json([
                'success' => false,
                'message' => 'un contract a déjà été etabli pour cette abonnement',
            ], 405);

        $file = $request->file('file');
        $fileName = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('contracts', $fileName, 'public');
        $fileType = $file->getClientMimeType();

        $contract = Contract::create([
            'student_id' => $user->id,
            'subscription_id' => $request->subscription_id,
            'file_original' => $filePath,
            'file_signed' => auth()->user()->firstname.' '.auth()->user()->lastname,
            'tag' => 'initial',
            'date' => new \DateTime(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $contract,
        ], 200);
    }

    /**
     * Update contract for Subcription
     * @requestMediaType multipart/form-data
     */
    public function updatecontract(Request $request, Contract $contract){

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB max
        ]);

        $file = $request->file('file');
        $fileName =  time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('contracts', $fileName, 'public');
        $fileType = $file->getClientMimeType();

        $contract->update([
            'file_original' => $filePath,
            'file_signed' => auth()->user()->firstname.' '.auth()->user()->lastname,
            'tag' => 'initial',
            'date' => new \DateTime(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $contract->fresh()
        ], 200);
    }

    /**
     * List contract services.
     * 
     */
    public function listcontract(User $user)
    {
        $contracts = Contract::where('student_id',$user->id)->with(['subscription.service'])->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $contracts,
        ], 200);
    }

    /**
     * List Learner to an exam.
     * 
     */
    public function ListLearnerToExam(Request $request)
    {
        $examens = Examen::get();

        return response()->json([
            'success' => true,
            'data' => $examens,
            'message' => "List Exam"
        ], 200);
    }

    /**
     * Add Learner to an exam.
     * 
     */
    public function addLearnerToExam(Request $request)
    {
        $validated = $request->validate([
            'instructor_id' => 'integer|required',
            'learner_id' => 'integer|required',
            'date' => 'date_format:Y-m-d H:i:s|required',
        ]);

        $examens = Examen::create([
            'instructor_id' => $validated['instructor_id'],
            'learner_id' => $validated['learner_id'],
            'date' => $validated['date'],
            'status' => "pending",
        ]);

        return response()->json([
            'success' => true,
            'data' => $examens,
            'message' => "Créer avec succès"
        ], 200);
    }

    /**
     * Update exam.
     * 
     */
    public function updateLearnerToExam(Request $request, Examen $examen)
    {
        $validated = $request->validate([
            'instructor_id' => 'integer|required',
            'learner_id' => 'integer|required',
            'date' => 'date_format:Y-m-d H:i:s',
        ]);

        $examen->instructor_id = $validated['instructor_id'];
        $examen->learner_id = $validated['learner_id'];
        $examen->date = $validated['date'];
        $examen->save();

        return response()->json([
            'success' => true,
            'data' => $examen,
            'message' => "Mis-à-jour"
        ], 200);
    }

    /**
     * Delete exam.
     * 
     */
    public function deleteLearnerToExam(Request $request, $examen)
    {
        $find = Examen::find($examen);

        if($find) {
            $find->delete();

            return response()->json([
                'success' => true,
                'message' => "Supprimer avec succès",
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Inexistant",
            ], 404);
        }

        
    }
}
