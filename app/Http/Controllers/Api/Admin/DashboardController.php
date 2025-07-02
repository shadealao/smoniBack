<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

use App\Models\Withdraw;
use App\Models\User;
use App\Models\Appointment;
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

        $hour_billable = Appointment::where('status', 'completed')->sum('duration') - Withdraw::sum('duration');
        $hour_no_billable = Appointment::where('status', 'notation')->sum('duration');
    

        return response()->json([
            'success' => true,
            'data' => [
                'learner' => $learner,
                'instructor' => $instructor,
                'admin' => $admin,
                'hour_billable' => $hour_billable,
                'hour_no_billable' => $hour_no_billable,
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

    
}
