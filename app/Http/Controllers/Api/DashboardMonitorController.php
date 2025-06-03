<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamRegistration;
use App\Models\Appointment;
use App\Http\Resources\AppointmentLearnerResource;
use App\Models\Availability;

class DashboardMonitorController extends Controller
{
    /**
     * Bilan Dashboard
     */
    public function stat(){
        $learners_count = Appointment::query()
        ->select('learner_id')
        ->where('instructor_id', auth()->user()->id)
        ->groupBy('learner_id')
        ->count('learner_id');

        $cash = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'completed')->sum('duration');

        $count_learners_exam = ExamRegistration::where([
            'monitor_id' =>  auth()->user()->id,
            'status' => 'registered',
        ])->count();

        $rdv_pending = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'scheduled')->count();

        return response()->json([
            'success' => true,
            'rdv_pending' => $rdv_pending ,
            'cash' => $cash ,
            'learners_count' => $learners_count ,
            'count_learners_exam' => $count_learners_exam ,
        ], 200);

    }

    /**
     * Last Learner By Instrutor
     */
    public function listLearner(Request $request)
    {
        if(auth()->user()->role != 'instructor')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un instructeur',
            ], 403);

        $learners = Appointment::query()
            ->select('learner_id','id')
            ->selectRaw('SUM(CASE WHEN status = \'completed\' THEN duration ELSE 0 END) as total_duration')
            ->where('instructor_id', auth()->user()->id)
            ->groupBy('learner_id')
            ->groupBy('id')
            ->limit(10)
            ->get();

        return AppointmentLearnerResource::collection($learners);
    }

    /**
     * List Lesson
     */
    public function lists(Request $request)
    {
        if(auth()->user()->role != 'instructor')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un instructeur',
            ], 403);

        $appointments = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'confirmed')->with('learner')->orderBy('created_at','desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $appointments,
        ], 200);
    }
}
