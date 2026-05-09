<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Contract;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionRegistrationController extends Controller
{
    /**
     * Create a new subscription with payment and contract.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Only learners can create subscriptions for themselves
        if ($user->role !== 'learner') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les apprenants peuvent souscrire à un abonnement.',
            ], 403);
        }

        $validated = $request->validate([
            'plan_id' => 'required|exists:services,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'type_service' => 'required|in:on_site,online,hybrid',
            'auto_renewal' => 'boolean',
            'payment_method' => 'required|in:credit_card,bank_transfer,cash',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|in:EUR,USD', // Add more as needed
            'contract_file_original' => 'required|file|mimes:pdf|max:2048', // Original contract PDF, max 2MB
            'contract_tag' => 'required|in:initial,renewal,amendment',
        ]);

        $service = Service::findOrFail($validated['plan_id']);

        // Verify amount matches service price
        if ($validated['amount'] * 100 !== $service->price) {
            return response()->json([
                'success' => false,
                'message' => 'Le montant du paiement ne correspond pas au prix du service.',
            ], 422);
        }

        $subscription = DB::transaction(function () use ($user, $validated, $service) {
            // Store original contract file
            $originalFile = $validated['contract_file_original'];
            $originalFileName = 'original_' . time() . '_' . Str::random(10) . '.pdf';
            $originalFilePath = $originalFile->storeAs('contracts', $originalFileName, 'public');

            // Placeholder for signed contract (to be updated later)
            $signedFilePath = $originalFilePath; // Assume same file until signed

            // Create payment
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'payment_date' => now(),
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'invoice_number' => 'INV-' . Str::uuid(),
                'related_appointment_id' => null,
            ]);

            // Create subscription
            $subscription = Subscription::create([
                'learner_id' => $user->id,
                'service_id' => $service->id,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'type_service' => $validated['type_service'],
                'status' => 'active',
                'amount' => $validated['amount'],
                'transaction_id' => $payment->invoice_number,
            ]);

            // Create contract
            Contract::create([
                'subscription_id' => $subscription->id,
                'student_id' => $user->id,
                'file_original' => $originalFilePath,
                'file_signed' => $signedFilePath,
                'tag' => $validated['contract_tag'],
                'date' => now(),
            ]);

            return $subscription;
        });

        return response()->json([
            'success' => true,
            'data' => $subscription->load(['service', 'contract']),
            'message' => 'Abonnement créé avec succès.',
        ], 201);
    }

    /**
     * Record a payment status update (admin only).
     */
    public function recordPayment(Request $request, Payment $payment)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent mettre à jour les paiements.',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded',
            'payment_date' => 'required_if:status,completed|date',
        ]);

        $payment->update([
            'status' => $validated['status'],
            'payment_date' => $validated['status'] === 'completed' ? $validated['payment_date'] : $payment->payment_date,
        ]);

        // Note: Payment completion logic can be handled separately if needed

        return response()->json([
            'success' => true,
            'data' => $payment->refresh(),
            'message' => 'Paiement mis à jour avec succès.',
        ], 200);
    }

    /**
     * Update a signed contract file (admin only).
     */
    public function updateContract(Request $request, Contract $contract)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent mettre à jour les contrats.',
            ], 403);
        }

        $validated = $request->validate([
            'file_signed' => 'required|file|mimes:pdf|max:2048', // Signed contract PDF, max 2MB
        ]);

        // Delete old signed file if exists
        if ($contract->file_signed && Storage::disk('public')->exists($contract->file_signed)) {
            Storage::disk('public')->delete($contract->file_signed);
        }

        // Store new signed contract file
        $signedFile = $validated['file_signed'];
        $signedFileName = 'signed_' . time() . '_' . Str::random(10) . '.pdf';
        $signedFilePath = $signedFile->storeAs('contracts', $signedFileName, 'public');

        $contract->update([
            'file_signed' => $signedFilePath,
        ]);

        return response()->json([
            'success' => true,
            'data' => $contract->refresh()->load('subscription'),
            'message' => 'Contrat signé mis à jour avec succès.',
        ], 200);
    }

    /**
     * List all subscriptions for a learner.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Admins can view all subscriptions, learners only their own
        $subscriptions = $user->role === 'admin'
            ? Subscription::with(['learner', 'service', 'contract'])->get()
            : Subscription::where('learner_id', $user->id)
                ->with(['learner', 'service', 'contract'])
                ->get();

        return response()->json([
            'success' => true,
            'data' => $subscriptions,
            'message' => 'Liste des abonnements récupérée avec succès.',
        ], 200);
    }

    /**
     * Désactive une souscription
     */
    public function deactivate(Request $request, $subscriptionId)
    {
        $user = Auth::user();

        // Trouver la souscription
        $subscription = Subscription::findOrFail($subscriptionId);

        // Vérifier les permissions
        if ($user->role !== 'admin' && $subscription->learner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas l\'autorisation de désactiver cette souscription.',
            ], 403);
        }

        // Vérifier si la souscription est déjà inactive/annulée
        if ($subscription->isInactive()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette souscription est déjà annulée.',
            ], 400);
        }

        // Désactiver la souscription
        $subscription->deactivate();

        return response()->json([
            'success' => true,
            'data' => $subscription->load(['learner', 'service']),
            'message' => 'Souscription annulée avec succès.',
        ], 200);
    }

    /**
     * Réactive une souscription (admin seulement)
     */
    public function reactivate(Request $request, $subscriptionId)
    {
        $user = Auth::user();

        // Seuls les admins peuvent réactiver
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent réactiver une souscription.',
            ], 403);
        }

        // Trouver la souscription
        $subscription = Subscription::findOrFail($subscriptionId);

        // Vérifier si la souscription est déjà active
        if ($subscription->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette souscription est déjà active.',
            ], 400);
        }

        // Réactiver la souscription
        $subscription->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'data' => $subscription->load(['learner', 'service']),
            'message' => 'Souscription réactivée avec succès.',
        ], 200);
    }
}