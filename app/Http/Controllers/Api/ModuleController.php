<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StepModuleItem;
use App\Models\TrainingModule;
use App\Models\ModuleStep;
use App\Models\Appointment;
use App\Models\LearnerProgres;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    /**
     * Learning Book
     */
    public function store(Request $request,Appointment $appointment){

        if (auth()->user()->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peut mettre à jour la progression.',
            ], 403);
        }

        $validated = $request->validate([
            'competences'=>'required|array',
            'competences.*' => 'required|exists:step_module_items,id',
        ]);

        DB::beginTransaction();

            $appointment->update([
                'status' => 'completed',
            ]);
            foreach ($request->competences as $competence) {
                $progress = LearnerProgres::create([
                    'appointment_id' => $appointment->id, 
                    'learner_id' => auth()->user()->id, 
                    'step_item_id' => $competence, 
                ]);
            }



        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Progression mise à jour avec succès.',
        ], 200); 

    }

    /**
     * Learner Progress
     */
    public function index(User $user){

        $myCompetence = LearnerProgres::where('learner_id',$user->id)->pluck('step_item_id')->toArray();
        
        $trainingModules = TrainingModule::all();

        $module = array();
        foreach ($trainingModules as $trainingModule) {
            
            $subModule = array();
            $total_check_comp = 0;
            $total_comp = 0;
            foreach ($trainingModule->steps as $moduleStep) {
            
                $compet = array();
                $check=0;
                foreach ($moduleStep->competences as $competence) {
                    
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

        }

        return response()->json([
            'success' => true,
            'data' => $module,
        ], 200); 
        
    }
}
