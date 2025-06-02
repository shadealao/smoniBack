<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    /**
     * Register a new bank account (instructor only).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent enregistrer des comptes bancaires.',
            ], 403);
        }

        $validated = $request->validate([
            'iban' => 'required|string|max:34|regex:/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{1,30}$/',
            'bic' => 'required|string|max:11|regex:/^[a-zA-Z]{6}[a-zA-Z0-9]{2}([a-zA-Z0-9]{3})?$/',
            'bank_name' => 'required|string|max:255',
        ]);

        $bankAccount = BankAccount::create([
            'monitor_id' => $user->id,
            'iban' => $validated['iban'],
            'bic' => $validated['bic'],
            'bank_name' => $validated['bank_name'],
            'status' => true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $bankAccount,
            'message' => 'Compte bancaire enregistré avec succès.',
        ], 201);
    }

    /**
     * Get details of a specific bank account.
     */
    public function show(BankAccount $bankAccount)
    {
        $user = Auth::user();

        if ($bankAccount->monitor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à voir ce compte bancaire.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $bankAccount,
            'message' => 'Détails du compte bancaire récupérés avec succès.',
        ], 200);
    }

    /**
     * List all bank accounts for the authenticated instructor.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'instructor') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les moniteurs peuvent voir la liste des comptes bancaires.',
            ], 403);
        }

        $bankAccounts = BankAccount::where('monitor_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'data' => $bankAccounts,
            'message' => 'Liste des comptes bancaires récupérée avec succès.',
        ], 200);
    }

    /**
     * Delete a bank account.
     */
    public function destroy(BankAccount $bankAccount)
    {
        $user = Auth::user();

        if ($bankAccount->monitor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à supprimer ce compte bancaire.',
            ], 403);
        }

        $bankAccount->delete();

        return response()->json([
            'success' => true,
            'message' => 'Compte bancaire supprimé avec succès.',
        ], 200);
    }

    /**
     * Update a bank account.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $user = Auth::user();

        if ($bankAccount->monitor_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce compte bancaire.',
            ], 403);
        }

        $validated = $request->validate([
            'iban' => 'sometimes|string|max:34|regex:/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{1,30}$/',
            'bic' => 'sometimes|string|max:11|regex:/^[a-zA-Z]{6}[a-zA-Z0-9]{2}([a-zA-Z0-9]{3})?$/',
            'bank_name' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
        ]);

        $bankAccount->update($validated);

        return response()->json([
            'success' => true,
            'data' => $bankAccount->fresh(),
            'message' => 'Compte bancaire mis à jour avec succès.',
        ], 200);
    }
}