<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @tags Zone Admin (Admin)
 */

class AdminController extends Controller
{
     /**
     * List
     */
    public function index(Request $request){
        $q = $request->q ? : '';
        $users = User::where('role','admin')->whereNot('id',auth()->user()->id)      
            ->where(function ($query) use ($q) {
                $query->where(DB::raw('lower(lastname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(firstname)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(email)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(phone)'),'like','%'.strtolower($q).'%');
            })->orderBy('created_at','desc')->paginate(10);

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
}
