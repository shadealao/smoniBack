<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WithdrawController extends Controller
{

    /**
     * Wallet Info.
     */
    public function stat(){
        $hour_billable = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'completed')->sum('duration') - Withdraw::where('monitor_id', auth()->user()->id)->sum('duration');
        $billable = [
            'hour' => $hour_billable,
            'cash' => $hour_billable * auth()->user()->instructorProfile->hourPrice,
        ];

        $hour_no_billable = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'notation')->sum('duration');
        $no_billable = [
            'hour' => $hour_no_billable,
            'cash' => $hour_no_billable * auth()->user()->instructorProfile->hourPrice,
        ];

        $admin_cash = $hour_billable * auth()->user()->instructorProfile->hourDiscount;
        $tva_cash = $hour_billable * auth()->user()->instructorProfile->tva;
        $my_cash = $billable['cash'] - ($admin_cash + $tva_cash);

        return response()->json([
            'success' => true,
            'billable' => $billable ,
            'no_billable' => $no_billable ,
            'admin_cash' => $admin_cash,
            'tva_cash' => $tva_cash,
            'my_cash' => $my_cash,
        ], 200);

    }

    /**
     * List No-billable
     */
    public function no_billable(Request $request)
    {
        if(auth()->user()->role != 'instructor')
            return response()->json([
                'success' => false,
                'message' => 'Cette utilisateur n\'est pas un instructeur',
            ], 403);

        $appointments = Appointment::where([
            'instructor_id' => auth()->user()->id,
            'status' => 'notation'
        ])->with('learner')->orderBy('created_at','desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $appointments,
        ], 200);
    }



    /**
     * Register a new withdrawal request (instructor only).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent demander des retraits.',
            ], 403);
        }

        $validated = $request->validate([
            // 'ammount' => 'required|integer|min:100',
            // 'duration' => 'required|integer|min:1|max:30',
            // 'currency' => 'required|string|in:EUR,USD',
            'numero' => 'required|string',
        ]);

        $hour = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'completed')->sum('duration') - Withdraw::where('monitor_id', auth()->user()->id)->sum('duration');
        $cash = $hour * auth()->user()->instructorProfile->hourPrice;

        
        
        $withdraw = Withdraw::create([
            'monitor_id' => $user->id,
            'ammount' => $cash,
            'duration' => $hour,
            'currency' => 'EUR',
            'payed' => false,
            'invoice_code' => $request->numero,
        ]);

        return response()->json([
            'success' => true,
            'data' => $withdraw,
            'message' => 'Demande de retrait enregistrée avec succès.',
        ], 201);
    }

    /**
     * View all withdrawal requests (admin only).
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|string',
            'per_page' => 'integer'
        ]);

        $user = Auth::user();
        $per_page = $request->per_page ?? 10;

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent voir les demandes de retrait.',
            ], 403);
        }

        if($request->status)
            $withdraws = Withdraw::where('payed',$request->status)->with('monitor')->paginate($per_page);
        else
            $withdraws = Withdraw::where('payed',$request->status)->with('monitor')->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $withdraws,
            'message' => 'Liste des demandes de retrait récupérée avec succès.',
        ], 200);
    }

    /**
     * View all withdrawal requests (Instructor).
     */
    public function list(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|string',
            'per_page' => 'integer'
        ]);
        $user = Auth::user();
        $per_page = $request->per_page ?? 10;

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent demander des retraits.',
            ], 403);
        }

        if($request->status)
            $withdraws = Withdraw::where('monitor_id',$user->id)->where('payed',$request->status)->with('monitor')->paginate($per_page);
        else
            $withdraws = Withdraw::where('monitor_id',$user->id)->with('monitor')->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $withdraws,
            'message' => 'Liste des demandes de retrait récupérée avec succès.',
        ], 200);
    }

    /**
     * Approve a withdrawal request (admin only).
     */
    public function approve(Request $request, Withdraw $withdraw)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent approuver les retraits.',
            ], 403);
        }

        if ($withdraw->payed) {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande de retrait a déjà été payée.',
            ], 422);
        }

        $withdraw->update([
            'payed' => true,
        ]);

        // Here you could integrate with a payment gateway (e.g., Stripe) to process the payment
        // Example: $payment = Stripe::payouts()->create([...]);

        return response()->json([
            'success' => true,
            'data' => $withdraw->fresh(),
            'message' => 'Demande de retrait approuvée avec succès.',
        ], 200);
    }

    /**
     * Generate a unique invoice code.
     */
    protected function generateInvoiceCode()
    {
        return 'INV-' . strtoupper(uniqid());
    }
}