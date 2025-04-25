<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\MeetingPointController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/password/send-otp', [UserController::class, 'sendOtpCode']);
Route::post('/password/verify-otp', [UserController::class, 'verifyOtpCode']);
Route::post('/password/reset', [UserController::class, 'updatePassword']);

Route::middleware('auth:sanctum')->group(function () {
    // Update profile routes
    Route::put('/profile/update/learner', [UserController::class, 'updateLearnerProfile']);
    Route::put('/profile/update/instructor', [UserController::class, 'updateInstructorProfile']);

    // View profile routes
    Route::get('/profile/learner', [UserController::class, 'viewLearnerProfile']);
    Route::get('/profile/instructor', [UserController::class, 'viewInstructorProfile']);
    Route::get('/profile/admin', [UserController::class, 'viewAdminProfile']);

    // Meeting Point Routes
    Route::post('/meeting-points', [MeetingPointController::class, 'store']);
    Route::get('/meeting-points', [MeetingPointController::class, 'index']);
    Route::get('/meeting-points/{meetingPoint}', [MeetingPointController::class, 'show']);
    Route::put('/meeting-points/{meetingPoint}', [MeetingPointController::class, 'update']);
    Route::delete('/meeting-points/{meetingPoint}', [MeetingPointController::class, 'destroy']);

    // Vehicle Routes
    Route::post('/vehicles', [VehicleController::class, 'store']);
    Route::get('/vehicles', [VehicleController::class, 'index']);
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update']);
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy']);

    // Availability Routes
    Route::post('/availabilities', [AvailabilityController::class, 'store']);
    Route::get('/availabilities', [AvailabilityController::class, 'index']);
    Route::get('/availabilities/{availability}', [AvailabilityController::class, 'show']);
    Route::put('/availabilities/{availability}', [AvailabilityController::class, 'update']);
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy']);

});
