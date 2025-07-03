<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use App\Models\Withdraw;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\Subscription;
use Illuminate\Http\Request;

/**
 * @tags Zone Dashboard (Admin)
 */
class DashboardController extends Controller
{
    /**
     * Stat
     */
    public function index(){

        $learner = User::where('role','learner')->count();
        $instructor = User::where('role','instructor')->count();
        $admin = User::where('role','admin')->count();

        $cash = Subscription::sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'learner' => $learner,
                'instructor' => $instructor,
                'admin' => $admin,
                'cash' => $cash,
            ],
            'message' => 'Succès.',
        ], 200);
    }

    /**
     * Graph
     */
    public function graph(){

        $pending = Appointment::where('status', 'pending')->count();
        $confirmed = Appointment::where('status', 'confirmed')->count();
        $scheduled = Appointment::where('status', 'scheduled')->count();
        $notation = Appointment::where('status', 'notation')->count();
        $cancelled = Appointment::where('status', 'cancelled')->count();
        $completed = Appointment::where('status', 'completed')->count();

        return response()->json([
            'success' => true,
            'pending' => $pending ,
            'confirmed' => $confirmed ,
            'scheduled' => $scheduled,
            'notation' => $notation,
            'cancelled' => $cancelled,
            'completed' => $completed,
        ], 200);
        
    }

    /**
     * Withdraws history
     */
    public function withdrawalYear(){
        // Récupérer l'année en cours
        $currentYear = Carbon::now()->year;

        // Récupérer les retraits pour l'année en cours
        // et les grouper par mois, en sommant les montants
        $monthlyWithdrawals = Withdraw::selectRaw('EXTRACT(MONTH FROM created_at) as month, SUM(ammount) as total_ammount')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Optionnel : Formater les résultats pour avoir le nom du mois
        $formattedWithdrawals = $monthlyWithdrawals->map(function ($item) {
            $monthName = Carbon::create(null, $item->month, 1)->monthName; // Nom du mois en français
            return [
                'month_number' => $item->month,
                'month_name' => ucfirst($monthName), // Mettre la première lettre en majuscule
                'total_ammount' => $item->total_ammount,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedWithdrawals,
            'message' => 'Succès.',
        ], 200);
    }
}
