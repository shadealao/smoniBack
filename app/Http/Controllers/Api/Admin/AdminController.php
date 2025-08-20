<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\BankAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

/**
 * @tags Zone Admin (Admin)
 */

class AdminController extends Controller
{
     /**
     * List
     */
    public function index(Request $request){
        $validated = $request->validate([
            'q' => '',
            'per_page' => 'integer',
        ]);
        $q = $request->q ? : '';
        $per_page = $request->per_page ? : 10;
        $users = User::where('role','admin')->whereNot('id',auth()->user()->id)      
            ->where(function ($query) use ($q) {
                $query->where(DB::raw('lower(lastname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(firstname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(email)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(phone)'),'like','%'.strtolower($q).'%');
            })->orderBy('created_at','desc')->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);
    }

    /**
     * Action (Active/Lock) 
     */
    public function action(Request $request, User $user){
        
        $user->update([
            'is_active' => $user->is_active ? false : true,
        ]);

        $this->sendmailer( auth()->user()->id, $user->is_active ? "Blocage du compte" : "Béblocage du compte",$user->is_active ? "Blocage du compte" : "Béblocage du compte", 'Vous venez de '.$user->is_active ? 'bloquer' : 'débloquer'.' l\utilisateur '.$user->lastname, 'UserAction');

        $this->sendmailer( $user->id, $user->is_active ? "Blocage du compte" : "Béblocage du compte",$user->is_active ? "Blocage du compte" : "Béblocage du compte", 'Votre compte aété '.$user->is_active ? 'bloqué' : 'débloqué', 'UserAction');

        return response()->json([
            'success' => true,
            'data' => $user->is_active ? 'Compte Activé' : 'Compte Bloqué',
        ], 200);
    }

    /**
     * Add Admin
     */
    public function addAdmin(Request $request)
    {
        // Validation des données
        // 'birthdate' => 'required_if:role,learner|date|before:today',

        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Hachage du mot de passe
        $validated['password'] = Hash::make($validated['password']);
        DB::beginTransaction();
        try{
            

            $user = User::create([
                'lastname' => $validated['lastname'],
                'firstname' => $validated['firstname'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'admin',
                'email_verified_at' => new \DateTime(),
            ]);

            DB::commit();

            // $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                // 'token' => $token,
                // 'data' => $user->load($validated['role'] === 'learner' ? 'learnerProfile' : ($validated['role'] === 'instructor' ? 'instructorProfile' : [])),
                'message' => 'Utilisateur inscrit avec succès'
            ], 201);

        }catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage(),
                'error' => 'There was an error creating the user.'
            ], 500);
        } 
    }

    /**
     * Delete Admin
     */
    public function deleteAdmin(Request $request, User $user){
        
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Compte bien supprimé',
        ], 200);
    }

    /**
     * View all withdrawal requests.
     */
    public function withdraws(Request $request)
    {
        $validated = $request->validate([
            'status' => [Rule::in(['success', 'pending']),'nullable'],
            'per_page' => 'integer'
        ]);

        $per_page = $request->per_page ?? 10;

        if($request->status){
            $status = $request->status == 'success' ? true : false;
            $withdraws = Withdraw::where('payed',$status)->with('monitor.bank')->paginate($per_page);
        }
        else
            $withdraws = Withdraw::with('monitor')->paginate($per_page);

        return response()->json([
            'success' => true,
            'data' => $withdraws,
            'message' => 'Liste des demandes de retrait récupérée avec succès.',
        ], 200);
    }

    /**
     * Show withdrawal.
     */
    public function showWthdraw(Request $request, Withdraw $withdraw)
    {
        $bank = BankAccount::where('monitor_id', $withdraw->monitor_id)->first();

        $per_page = $request->per_page ?? 10;

        if($request->status){
            $status = $request->status == 'success' ? true : false;
            $withdraws = Withdraw::where('payed',$status)->with('monitor')->paginate($per_page);
        }
        else
            $withdraws = Withdraw::with('monitor')->paginate($per_page);

        return response()->json([
            'success' => true,
            'withdraw' => $withdraw->load('monitor'),
            'bank' => $bank,
        ], 200);
    }

     /**
     * Approve a withdrawal request (admin only).
     */
    public function approve(Request $request, Withdraw $withdraw)
    {
        if ($withdraw->payed) {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande de retrait a déjà été payée.',
            ], 422);
        }

        $withdraw->update([
            'payed' => true,
        ]);
        
        $this->sendmailer( auth()->user()->id, 'Validation de la Demande de Retrait', 'Validation de la Demande de Retrait', 'Vous venez de valider un retrait de '.$hour.' heure soit un montant de '.$cash.' EUR pour le moniteur '.$withdraw->monitor->lastname.' '.$withdraw->monitor->firstname, 'withdraw');
        $this->sendmailer( $withdraw->monitor_id, 'Validation de la Demande de Retrait', 'Validation de la Demande de Retrait', 'Vous venez de recevoir un retrait de '.$hour.' heure soit un montant de '.$cash.' EUR. Merci et à bientôt', 'withdraw');

        return response()->json([
            'success' => true,
            'data' => $withdraw->fresh(),
            'message' => 'Demande de retrait approuvée avec succès.',
        ], 200);
    }

     /**
     * Delete a withdrawal request (admin only).
     */
    public function decline(Request $request, Withdraw $withdraw)
    {

        if ($withdraw->payed) {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande de retrait a déjà été payée.',
            ], 422);
        }

        $withdraw->delete();

        $this->sendmailer( $withdraw->monitor_id, 'Annnulation de la Demande de Retrait', 'Annnulation de la Demande de Retrait', 'Votre retrait de '.$withdraw->duration.' heure soit un montant de '.$withdraw->ammount.' EUR a été annulé. Veuillez joindre l\'equipe', 'withdraw');

        return response()->json([
            'success' => true,
            'message' => 'Demande de retrait annnulé avec succès.',
        ], 200);
    }

}
