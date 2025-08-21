<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LearnerController extends Controller
{
    public function index(Request $request){
        $q = $request->q ? : '';
        $users = User::where('role','learner')      
            ->where(function ($query) use ($q) {
                $query->where(DB::raw('lower(lastname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(firstname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(email)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(phone)'),'like','%'.strtolower($q).'%');
            })->with('learnerProfile')->orderBy('created_at','desc')->paginate(10);

        return view('admin.file.learners.index',compact('users','q'));
    }

    public function action(User $user){
        
        $user->update([
            'is_active' => $user->is_active ? false : true,
        ]);

        return back()->with('success','Succès');
    }

    public function userBadges(User $user)
    {
        $badges = Badge::where('learner_id',$user->id)->with('list_badge')->get();
        $ids = Badge::where('learner_id',$user->id)->pluck('list_badge_id')->toArray();

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
     * User Progress
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
}
