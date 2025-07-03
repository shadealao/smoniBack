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
    public function withdrawalYear(Request $request)
    {

        $validated = $request->validate([
            'year' => 'required',
        ]);

        $currentYear = $request->year;

        $monthlyData = [];
        for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++) {
            $monthName = Carbon::create(null, $monthNumber, 1)->monthName;

            $monthlyData[$monthNumber] = [
                'month_number' => $monthNumber,
                'month_name' => ucfirst($monthName),
                'total_ammount' => 0, 
            ];
        }

        $withdrawals = Withdraw::whereYear('created_at', $currentYear)->get();
        foreach ($withdrawals as $withdraw) {
            $month = Carbon::parse($withdraw->created_at)->month;

            if ($month >= 1 && $month <= 12) {
                $monthlyData[$month]['total_ammount'] += $withdraw->ammount;
            }
        }
        $formattedWithdrawals = array_values($monthlyData);

        return response()->json([
            'success' => true,
            'data' => $formattedWithdrawals,
            'message' => 'Historique des retraits par mois récupéré avec succès.',
        ], 200);
    }
}
