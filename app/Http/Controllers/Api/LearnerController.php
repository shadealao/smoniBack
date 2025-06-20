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
use Illuminate\Validation\Rule;

class LearnerController extends Controller
{
    /**
     * Badges Users
     */
    public function userBadges()
    {
        $badges = Badge::where('learner_id',auth()->user()->id)->with('list_badge')->get();
        $ids = Badge::where('learner_id',auth()->user()->id)->pluck('id')->toArray();

        $nobadges = ListBadge::whereNotIn('id',$ids)->get();

        return response()->json([
            'success' => true,
            'data' => [
                "badges" => $badges,
                "nobadges" => $nobadges,
            ],
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
     * Lesson
     */
    public function lessonLearner(Request $request)
    {
        $lessons = Appointment::where('learner_id', auth()->user()->id)->with('instructor')->with('availability.meetingPoint')->orderBy('created_at','desc')->get();

        return response()->json([
            'success' => true,
            'data' => $lessons,
        ], 200);
    }
}
