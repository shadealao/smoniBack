<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){

        $learner = User::where('role','learner')->count();
        $instructor = User::where('role','instructor')->count();
        $admin = User::where('role','admin')->count();

        $hour_billable = Appointment::where('status', 'completed')->sum('duration') - Withdraw::sum('duration');
        $billable = [
            'hour' => $hour_billable,
            'cash' => $hour_billable * auth()->user()->instructorProfile->hourPrice,
        ];

        $hour_no_billable = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'notation')->sum('duration');
        $no_billable = [
            'hour' => $hour_no_billable,
            'cash' => $hour_no_billable * auth()->user()->instructorProfile->hourPrice,
        ];

        $admin_cash = auth()->user()->instructorProfile->hourDiscount;
        $tva_cash = auth()->user()->instructorProfile->tva;
        $my_cash = $billable['cash'] - ($admin_cash + $tva_cash);

        return response()->json([
            'success' => true,
            'billable' => $billable ,
            'no_billable' => $no_billable ,
            'admin_cash' => $admin_cash,
            'tva_cash' => $tva_cash,
            'my_cash' => $my_cash,
        ], 200);




        return response()->json([
            'success' => true,
            'data' => [
                'learner' => $learner,
                'instructor' => $instructor,
                'admin' => $admin
            ],
            'message' => 'Profil Admin mis à jour avec succès.',
        ], 200);
    }

    
}
