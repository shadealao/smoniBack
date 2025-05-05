<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportTicketController extends Controller
{
    /**
     * Create a new support ticket.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'open',
            'assigned_to' => null,
            'resolved_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $ticket->load(['user', 'assignee']),
            'message' => 'Ticket de support créé avec succès.',
        ], 201);
    }

    /**
     * List all tickets for a user or all tickets (admin only).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $tickets = $user->role === 'admin'
            ? SupportTicket::with(['user', 'assignee'])->get()
            : SupportTicket::where('user_id', $user->id)->with(['user', 'assignee'])->get();

        return response()->json([
            'success' => true,
            'data' => $tickets,
            'message' => 'Liste des tickets de support récupérée avec succès.',
        ], 200);
    }

    /**
     * Assign a ticket to an admin (admin only).
     */
    public function assign(Request $request, SupportTicket $supportTicket)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent assigner des tickets.',
            ], 403);
        }

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        // Ensure assigned_to is an admin
        $assignee = User::findOrFail($validated['assigned_to']);
        if ($assignee->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Le ticket ne peut être assigné qu\'à un administrateur.',
            ], 422);
        }

        $supportTicket->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'in_progress',
        ]);

        return response()->json([
            'success' => true,
            'data' => $supportTicket->refresh()->load(['user', 'assignee']),
            'message' => 'Ticket assigné avec succès.',
        ], 200);
    }

    /**
     * Update ticket status (admin only).
     */
    public function updateStatus(Request $request, SupportTicket $supportTicket)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les administrateurs peuvent mettre à jour le statut des tickets.',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
        ]);

        $supportTicket->update([
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $supportTicket->refresh()->load(['user', 'assignee']),
            'message' => 'Statut du ticket mis à jour avec succès.',
        ], 200);
    }

    /**
     * Add a response to a ticket (user or admin).
     */
    public function addResponse(Request $request, SupportTicket $supportTicket)
    {
        $user = Auth::user();

        // Only the ticket creator or an admin can add a response
        if ($user->id !== $supportTicket->user_id && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à répondre à ce ticket.',
            ], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        // Append response to message with user and timestamp
        $newMessage = $supportTicket->message . "\n\n" .
                      "[" . now()->toDateTimeString() . "] " .
                      ($user->role === 'admin' ? 'Admin' : 'Utilisateur') . " (" . $user->email . "):\n" .
                      $validated['message'];

        $supportTicket->update([
            'message' => $newMessage,
            'status' => $user->role === 'admin' ? $supportTicket->status : 'in_progress',
        ]);

        return response()->json([
            'success' => true,
            'data' => $supportTicket->refresh()->load(['user', 'assignee']),
            'message' => 'Réponse ajoutée avec succès.',
        ], 200);
    }
}