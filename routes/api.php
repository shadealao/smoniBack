<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\BankAccountController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ExamNoteController;
use App\Http\Controllers\Api\LearnerProgressController;
use App\Http\Controllers\Api\MeetingPointController;
use App\Http\Controllers\Api\SubscriptionRegistrationController;
use App\Http\Controllers\Api\SubscriptionServiceController;
use App\Http\Controllers\Api\SupportTicketController;
use App\Http\Controllers\Api\TrainingModuleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserDocController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\WithdrawController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Verification Email
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'emailVerify'])->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'verificationNotification'])->middleware(['throttle:6,1'])->name('verification.send');

//Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/password/send-otp', [UserController::class, 'sendOtpCode']);
Route::post('/password/verify-otp', [UserController::class, 'verifyOtpCode']);
Route::post('/password/reset', [UserController::class, 'updatePassword']);

// TrainingModule Routes
Route::get('/training-modules', [TrainingModuleController::class, 'index']);
// SubscriptionService Routes
Route::get('/services', [SubscriptionServiceController::class, 'index']);

Route::get('/meeting-points/search', [MeetingPointController::class, 'get_meeting_points']);

Route::middleware('auth:sanctum')->group(function () {
    // Update profile routes
    Route::put('/profile/update/learner', [UserController::class, 'updateLearnerProfile']);
    Route::put('/profile/update/instructor', [UserController::class, 'updateInstructorProfile']);
    Route::post('/profile/update/photo', [UserController::class, 'updateImage']);
    Route::put('/profile/update/dropPhoto', [UserController::class, 'dropImage']);

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

    // Appointment Routes
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
    Route::post('/appointments/{appointment}/presence', [AppointmentController::class, 'markPresence']);
    Route::post('/appointments/{appointment}/finished', [AppointmentController::class, 'markFinished']);

    // UserDoc Routes
    Route::post('/user-docs', [UserDocController::class, 'store']);
    Route::get('/user-docs', [UserDocController::class, 'index']);
    Route::delete('/user-docs/{userDoc}', [UserDocController::class, 'destroy']);

    // BankAccount Routes
    Route::post('/bank-accounts', [BankAccountController::class, 'store']);
    Route::get('/bank-accounts', [BankAccountController::class, 'index']);
    Route::get('/bank-accounts/{bankAccount}', [BankAccountController::class, 'show']);
    Route::put('/bank-accounts/{bankAccount}', [BankAccountController::class, 'update']);
    Route::delete('/bank-accounts/{bankAccount}', [BankAccountController::class, 'destroy']);

    // Withdraw Routes
    Route::post('/withdraws', [WithdrawController::class, 'store']);
    Route::get('/withdraws', [WithdrawController::class, 'index']);
    Route::post('/withdraws/{withdraw}/approve', [WithdrawController::class, 'approve']);

    // TrainingModule Routes
    Route::post('/training-modules', [TrainingModuleController::class, 'store']);
    Route::put('/training-modules/{trainingModule}', [TrainingModuleController::class, 'update']);
    Route::delete('/training-modules/{trainingModule}', [TrainingModuleController::class, 'destroy']);

    // LearnerProgress Routes
    Route::post('/learning-histories/{learningHistory}/track', [LearnerProgressController::class, 'trackProgress']);
    Route::post('/learner-progress/{learnerProgress}/badge', [LearnerProgressController::class, 'awardBadge']);
    Route::get('/learners/{learner}/badges', [LearnerProgressController::class, 'listBadges']);
    Route::get('/learners/{learner}/progress', [LearnerProgressController::class, 'listProgress']);

    // SubscriptionService Routes
    Route::post('/services', [SubscriptionServiceController::class, 'store']);
    Route::put('/services/{service}', [SubscriptionServiceController::class, 'update']);
    Route::delete('/services/{service}', [SubscriptionServiceController::class, 'destroy']);

    // SubscriptionRegistration Routes
    Route::post('/subscriptions', [SubscriptionRegistrationController::class, 'store']);
    Route::post('/payments/{payment}/record', [SubscriptionRegistrationController::class, 'recordPayment']);
    Route::post('/contracts/{contract}/update', [SubscriptionRegistrationController::class, 'updateContract']);
    Route::get('/subscriptions', [SubscriptionRegistrationController::class, 'index']);

    // ExamNote Routes
    Route::post('/exam-registrations', [ExamNoteController::class, 'registerExam']);
    Route::put('/exam-registrations/{examRegistration}', [ExamNoteController::class, 'updateExamResult']);
    Route::post('/notes', [ExamNoteController::class, 'createNote']);
    Route::get('/learners/{learner}/exam-registrations', [ExamNoteController::class, 'listExamRegistrations']);
    Route::get('/learners/{student}/notes', [ExamNoteController::class, 'listNotes']);

// SupportTicket Routes
    Route::post('/support-tickets', [SupportTicketController::class, 'store']);
    Route::get('/support-tickets', [SupportTicketController::class, 'index']);
    Route::post('/support-tickets/{supportTicket}/assign', [SupportTicketController::class, 'assign']);
    Route::put('/support-tickets/{supportTicket}/status', [SupportTicketController::class, 'updateStatus']);
    Route::post('/support-tickets/{supportTicket}/response', [SupportTicketController::class, 'addResponse']);

    // Évaluations
    Route::prefix('students/{student}')->group(function () {
        Route::apiResource('evaluations', EvaluationController::class);
        Route::get('latest-evaluation', [EvaluationController::class, 'latest']);
        Route::post('evaluations/{evaluation}/accept-proposal', [EvaluationController::class, 'acceptProposal']);
        
        // Expérience de conduite
        // Route::apiResource('driving-experiences', DrivingExperienceController::class)->only(['show', 'update']);
        
        // // Connaissance véhicule
        // Route::apiResource('vehicle-knowledge', VehicleKnowledgeController::class)->only(['show', 'update']);
    });
    
    // Statistiques
    Route::get('evaluation-stats', [EvaluationController::class, 'stats']);
});
