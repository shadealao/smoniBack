<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Exports\WithdrawsExport;
use Maatwebsite\Excel\Facades\Excel;

use PDF;

class WithdrawController extends Controller
{

    /**
     * Wallet Info.
     */
    public function stat(){
        $hour_billable = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'completed')->sum('duration')/60 - Withdraw::where('monitor_id', auth()->user()->id)->sum('duration');
        $billable = [
            'hour' => $hour_billable,
            'cash' => $hour_billable * auth()->user()->instructorProfile->hourPrice,
        ];

        $hour_no_billable = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'notation')->sum('duration')/60;;
        $no_billable = [
            'hour' => $hour_no_billable,
            'cash' => $hour_no_billable * auth()->user()->instructorProfile->hourPrice,
        ];

        // $admin_cash = $billable['cash'] == 0 ? 0 : auth()->user()->instructorProfile->hourDiscount;
        $tva_cash = ($billable['cash'] * auth()->user()->instructorProfile->tva)/100;
        $my_cash = $billable['cash'] + $tva_cash;

        $pendingWithdraw = Withdraw::where('monitor_id', auth()->user()->id)->where('payed',false)->count();

        return response()->json([
            'success' => true,
            'billable' => $billable ,
            'no_billable' => $no_billable ,
            // 'admin_cash' => $admin_cash,
            'tva_cash' => $tva_cash,
            'my_cash' => $my_cash,
            'pendingWithdraw' => $pendingWithdraw,
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

            $hour = Appointment::where('instructor_id', auth()->user()->id)->where('status', 'completed')->sum('duration')/60 - Withdraw::where('monitor_id', auth()->user()->id)->sum('duration');
            $cash = $hour * auth()->user()->instructorProfile->hourPrice;   
            
            if($cash == 0)
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible',
                ], 403);
            $withdraw = Withdraw::create([
                'monitor_id' => $user->id,
                'ammount' => $cash,
                'duration' => $hour,
                'currency' => 'EUR',
                'payed' => false,
                'invoice_code' => $request->numero,
                // 'invoice_file' => $pdf,
            ]);

            $this->sendmailer( $user->id, 'Demande de Retrait', 'Demande de Retrait', 'Vous venez de lancer un retrait de '.$hour.' heure soit un montant de '.$cash.' EUR. Veillez patienter que l\'administrateurs valide ce dernier', 'withdraw');

        return response()->json([
            'success' => true,
            'data' => $withdraw,
            'message' => 'Demande de retrait enregistrée avec succès.',
        ], 201);
    }

    
    /**
     * Generate facture.
     */
    public function generate()
    {
        
        $withdraws = Withdraw::where('invoice_file',null)->get();


        foreach ($withdraws as $withdraw) {
            DB::beginTransaction();

                $pdf = 'storage/pdf/facture/'.$withdraw->invoice_code.'_'.time().'.pdf';

                $user = User::find($withdraw->monitor_id);

                $amount = ($user->instructorProfile->hourPrice * $withdraw->duration );
                $admin = $user->instructorProfile->hourDiscount;

                $tva = $amount* ($user->instructorProfile->tva/100);

                if (!file_exists(public_path('storage/pdf/facture/'))) {
                    mkdir(public_path('storage/pdf/facture/'), 0755, true);
                }
                
                PDF::loadView('pdf.facture', compact('withdraw','user','amount','tva','admin'))
                    ->setPaper('a4', 'portrait')
                    ->setWarnings(false)
                    ->save(public_path($pdf));
                    
            DB::commit();

            $withdraw->update([
              'invoice_file' => $pdf,
            ]);

        }

        return true;
    }

    /**
     * View all withdrawal requests (Instructor).
     */
    public function list(Request $request)
    {
        $validated = $request->validate([
            'status' => 'in:all,payed,nopayed',
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

        $status = $request->status == "all" ? null : ($request->status == "nopayed" ? false : true);
        if($status !== null)
            $withdraws = Withdraw::where('monitor_id',$user->id)->where('payed',$status)->with('monitor')->paginate($per_page);
        else
            $withdraws = Withdraw::where('monitor_id',$user->id)->with('monitor')->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $withdraws,
            'message' => 'Liste des demandes de retrait récupérée avec succès.',
        ], 200);
    }

    /**
     * Export
     */
    public function export(Request $request)
    {
        return Excel::download(new WithdrawsExport(auth()->user()->id), 'retraits.xlsx');
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

        
        
        $this->sendmailer( auth()->user()->id, 'Validation de la Demande de Retrait', 'Validation de la Demande de Retrait', 'Vous venez de valider un retrait de '.$hour.' heure soit un montant de '.$cash.' EUR pour le moniteur '.$withdraw->monitor->lastname.' '.$withdraw->monitor->firstname, 'withdraw');
        $this->sendmailer( $withdraw->monitor_id, 'Validation de la Demande de Retrait', 'Validation de la Demande de Retrait', 'Vous venez de recevoir un retrait de '.$hour.' heure soit un montant de '.$cash.' EUR. Merci et à bientôt', 'withdraw');

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