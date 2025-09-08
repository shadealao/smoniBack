<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Examen;
use App\Models\Appointment;
use App\Http\Resources\AppointmentLearnerResource;
use Illuminate\Support\Facades\DB;
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
        ->distinct('learner_id')
        ->count();

        $hour = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'completed')->sum('duration');

        $count_learners_exam = Examen::where([
            'instructor_id' =>  auth()->user()->id,
            'status' => 'pending',
        ])->count();

        $rdv_pending = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'scheduled')->count();
        $cash = $hour * auth()->user()->instructorProfile->hourPrice;
        $tva = ($cash * auth()->user()->instructorProfile->tva) / 100;
        return response()->json([
            'success' => true,
            'rdv_pending' => $rdv_pending ,
            'cash' => $cash + $tva ,
            'hour' => $hour ,
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
            ->select('learner_id')
            ->selectRaw('SUM(CASE WHEN status = \'completed\' THEN duration ELSE 0 END) as total_duration')
            ->where('instructor_id', auth()->user()->id)
            ->groupBy('learner_id')
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

            $now = new \DateTime();

        $appointments = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'confirmed')->where('date', '>=', $now->format('Y-m-d'))->with('learner')->with('availability.meetingPoint')->orderBy('date','asc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $appointments,
        ], 200);
    }

    /**
     * Graph
     */
    public function graph(Request $request){
        $instructorId = auth()->user()->id;
        $year = date('Y');

        $monthNames = [
            1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr', 5 => 'Mai', 6 => 'Juin',
            7 => 'Juil', 8 => 'Août', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
        ];

        $monthlyDurations = Appointment::query()
            ->select(
                DB::raw('EXTRACT(MONTH FROM updated_at) as month'), // Extrait le numéro du mois de la colonne 'date'
                DB::raw('SUM(duration)/60 as total_duration') // Calcule la somme des durées
            )
            ->where('status','completed')
            ->where('instructor_id', $instructorId) // Filtre pour l'instructeur spécifique
            ->whereYear('updated_at', $year)           // Filtre pour l'année spécifique
            ->groupBy('month')                   // Regroupe par mois
            ->orderBy('month')                   // Ordonne par mois pour avoir une séquence logique
            ->get();

        $graphData = [];
        $actualData = [];

        // Convertir les résultats de la base de données en un tableau associatif pour un accès facile
        foreach ($monthlyDurations as $data) {
            $actualData[$data->month] = $data->total_duration;
        }

        // Remplir le tableau final pour les 12 mois
        for ($month = 1; $month <= 12; $month++) {
            $graphData[] = [
                'month_number' => $month,
                'month' => $monthNames[$month], // Nom du mois
                'cash' => ($actualData[$month] ?? 0) * auth()->user()->instructorProfile->hourPrice, // Durée réelle ou 0 si aucune donnée pour ce mois
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $graphData,
        ], 200);

    }
}
