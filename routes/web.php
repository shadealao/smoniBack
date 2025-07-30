<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LearnerController;

// Route::get('/', function () {
//     return view('welcome');
// });

//AuthController        
Route:: get('/', [AuthController::class, 'login'])->name('auth.login');
Route::post('/check', [AuthController::class, 'check'])->name('auth.check');

Route:: get('/forget', [AuthController::class, 'forget'])->name('auth.forget');
Route::get('/reset', [AuthController::class, 'reset'])->name('auth.reset');
Route::get('/link-sended', [AuthController::class, 'linkSended'])->name('auth.link-sended');
Route::post('/exist', [AuthController::class, 'exist'])->name('auth.exist');
Route::put('/update_pass/{id}', [AuthController::class, 'update_pass'])->name('auth.update_pass');

Route::middleware(['admin'])->group(function () {
        
    // Route:: get('/profil', [DashboardController::class, 'profil'])->name('admin.profil');
    // Route:: put('/profil/update_info/{id}', [DashboardController::class, 'update_info'])->name('admin.update_info');
    // Route:: put('/profil/update_pass/{id}', [DashboardController::class, 'update_pass'])->name('admin.update_pass');
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // // Learners
    // Route::get('/learners', [LearnerController::class, 'index'])->name('admin.learners');
    // Route::put('/learners/{user}/action', [LearnerController::class, 'action'])->name('admin.learners.action');

    // // Admins
    // Route::get('/admins', [AdminController::class, 'index'])->name('admin.admins');
    // Route::post('/admins/create', [AdminController::class, 'create'])->name('admin.admins.create');
    // Route::put('/admins/{user}/update', [AdminController::class, 'update'])->name('admin.admins.update');
    // Route::put('/admins/{user}/action', [AdminController::class, 'action'])->name('admin.admins.action');

    // // owners
    // Route::get('/owners', [OwnerController::class, 'index'])->name('admin.owners');
    // Route::put('/owners/{user}/action', [OwnerController::class, 'action'])->name('admin.owners.action');
    // Route::get('/owners/{user}/properties', [OwnerController::class, 'properties'])->name('admin.owners.properties');
    // Route::get('/owners/{user}/visits', [OwnerController::class, 'visits'])->name('admin.owners.visits');
    // Route::get('/owners/{user}/wallets', [OwnerController::class, 'wallets'])->name('admin.owners.wallets');
    // Route::put('/owners/{user}/percent', [OwnerController::class, 'percent'])->name('admin.owners.percent');

    
    
    // // Categories
    // Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    // Route::post('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    // Route::put('/categories/{category}/update', [CategoryController::class, 'update'])->name('admin.categories.update');
    // Route::put('/categories/{category}/action', [CategoryController::class, 'action'])->name('admin.categories.action');

    // // Annonces
    // Route::get('/annonces', [AnnonceController::class, 'index'])->name('admin.annonces');
    // Route::post('/annonces/create', [AnnonceController::class, 'create'])->name('admin.annonces.create');
    // Route::put('/annonces/{annonce}/update', [AnnonceController::class, 'update'])->name('admin.annonces.update');
    // Route::put('/annonces/{annonce}/action', [AnnonceController::class, 'action'])->name('admin.annonces.action');

    // // Properties
    // Route::get('/properties', [PropertyController::class, 'index'])->name('admin.properties');
    // Route::put('/properties/{property}/action', [PropertyController::class, 'action'])->name('admin.properties.action');

    // // Visits
    // Route::get('/visits', [VisitController::class, 'index'])->name('admin.visits');
    // Route::put('/visits/{visit}/refund', [VisitController::class, 'refund'])->name('admin.visits.refund');
    // Route::put('/visits/{visit}/check', [VisitController::class, 'check'])->name('admin.visits.check');

    // // Withdraws
    // Route::get('/withdraws', [WithdrawController::class, 'index'])->name('admin.withdraws');
    // Route::put('/withdraws/{withdraw}/check', [WithdrawController::class, 'check'])->name('admin.withdraws.check');


    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
