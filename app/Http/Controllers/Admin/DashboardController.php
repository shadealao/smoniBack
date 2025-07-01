<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Note;
use App\Models\User;
use App\Models\Visite;
use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;

use DevRaeph\PDFPasswordProtect\Facade\PDFPasswordProtect;

class DashboardController extends Controller
{
    public function index(){
        return view('admin.file.dashboard');
    }

    
    public function profil(){
        return view('admin.file.profil');
    }

    public function update_info(Request $request, $id){

        // dd($request->input());
        $user = User::find($id);
        if($user->email != $request->email){
            Session::flash('danger', "Erreur dans le formulaire");
            $validation->validate($request, [
                'email' => 'required|email|max:255|unique:users',
            ]) ;
        }

        $validation = Validator::make($request->all(), [
            'lastname' => 'required|max:255',
            'firstname' => 'required|max:255',
            'phone' => 'required',
        ]);
        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $validation->validate($request, [
                'lastname' => 'required|max:255',
                'firstname' => 'required|max:255',
                'phone' => 'required',
            ]) ;
        }
        
        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success','Modification réalisée avec succès');
    }

    public function update_pass(Request $request,$id){
        //return $request->input();
        $user=User::find($id);
        $validation = Validator::make($request->all(), [
            'older' => 'required|min:6',
            'password' => 'required|min:6',
            'confirm' => 'required|same:password|min:6'
        ]);
        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $validation->validate($request, [
                'older' => 'required|min:6',
                'password' => 'required|min:6',
                'confirm' => 'required|same:password|min:6'
            ]) ;
        }
        if(Hash::check($request->older,$user->password))
            $user->password = Hash::make($request->password);
        else
            return back()->with('danger',"Revoir l'ancien mot de passe");
        $user->save();
        return back()->with('success',"Mot de passe modifié avec succès");
    }

}
