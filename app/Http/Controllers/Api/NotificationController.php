<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\ListBadge;
use App\Models\Notification;
use App\Models\LearnerProgres;
use App\Models\TrainingModule;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    /**
     * Notifications
     * 
     */
    public function userNotification(Request $request){
        
        $validated = $request->validate([
            'reserv' => [Rule::in(['all', 'reserv'])],
        ]);

        $reserv = $request->reserv == 'reserv' ? true : false;

        if($reserv)
            $notifications = Notification::where('receiver_id', auth()->user()->id)->where('type', 'appointment')->with('sender')->paginate(10);
        else
            $notifications = Notification::where('receiver_id', auth()->user()->id)->with('sender')->paginate(10);


        return response()->json([
            'success' => true,
            'data' => $notifications,
        ], 200);
    }

    /**
     * Mark All as read
     */
     public function allAsRead(){

        $notifications = Notification::where('receiver_id', auth()->user()->id)->where('type', 'appointment')->update([
            "read_at" => new \DateTime()
        ]);

        return response()->json([
            'success' => true,
            'data' => 'Tous les messages marqués comme lue',
        ], 200);

    }

    /**
     * Mark One as read
     */
    public function OneAsRead(Notification $notification){

        $notification->update([
            "read_at" => new \DateTime()
        ]);

        return response()->json([
            'success' => true,
            'data' => 'Le message a été marqué comme lue',
        ], 200);

    }

}