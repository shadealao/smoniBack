<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Notification;
use Illuminate\Http\Request;

class Notif extends Controller
{
    /**
     * List Notif
     */
    public function index(Request $request){
        $validated = $request->validate([
            'per_page' => 'integer|nullable',
        ]);
        $per_page = $request->per_page ?? 10;

        $notification = Notification::where('receiver_id',auth()->user()->id)->with('sender')->orderBy('created_at','desc')->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $notification,
        ], 200);
    }

    /**
     * Mask as Read One Notif
     */
    public function maskOneNotif(Notification $notification){
        
        $notification->update([
            'read_at' => new \DateTime()
        ]);

        return response()->json([
            'success' => true,
            'data' => 'Notification masqué comme lu',
        ], 200);
    }

    /**
     * Mask as read All Notif
     */
    public function maskAllNotif(){
        
        $notification = Notification::where('receiver_id',auth()->user()->id)->update([
            'read_at' => new \DateTime()
        ]);

        return response()->json([
            'success' => true,
            'data' => 'Notifications masquées comme lues',
        ], 200);
    }
}
