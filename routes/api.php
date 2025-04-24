<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Update profile routes
    Route::put('/profile/update/learner', [UserController::class, 'updateLearnerProfile']);
    Route::put('/profile/update/instructor', [UserController::class, 'updateInstructorProfile']);

    // View profile routes
    Route::get('/profile/learner', [UserController::class, 'viewLearnerProfile']);
    Route::get('/profile/instructor', [UserController::class, 'viewInstructorProfile']);
    Route::get('/profile/admin', [UserController::class, 'viewAdminProfile']);

});
