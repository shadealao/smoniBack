<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WithdrawController extends Controller
{
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
            'ammount' => 'required|integer|min:100', // Minimum 1.00 (in cents)
            'duration' => 'required|integer|min:1|max:30', // Duration in days
            'currency' => 'required|string|in:EUR,USD', // Supported currencies
            'invoice_file' => 'nullable|file|mimes:pdf|max:2048', // Optional invoice PDF, max 2MB
        ]);

        $invoiceFilePath = null;
        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            $fileName = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $invoiceFilePath = $file->storeAs('invoices', $fileName, 'public');
        }

        $withdraw = Withdraw::create([
            'monitor_id' => $user->id,
            'ammount' => $validated['ammount'],
            'duration' => $validated['duration'],
            'currency' => $validated['currency'],
            'payed' => false,
            // 'invoice_code' => $invoiceFilePath ? $this->generateInvoiceCode() : null,
            'invoice_code' => $this->generateInvoiceCode(),
            'invoice_file' => $invoiceFilePath,
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