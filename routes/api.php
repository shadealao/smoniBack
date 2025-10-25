<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\AvailabilityRepeatedController;
use App\Http\Controllers\Api\BankAccountController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ExamNoteController;
use App\Http\Controllers\Api\LearnerProgressController;
use App\Http\Controllers\Api\MeetingPointController;
use App\Http\Controllers\Api\SubscriptionRegistrationController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SupportTicketController;
use App\Http\Controllers\Api\TrainingModuleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserDocController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\WithdrawController;
use App\Http\Controllers\Api\DashboardMonitorController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\LearnerController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Api\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\LearnerController as AdminLearnerController;
use App\Http\Controllers\Api\Admin\MonitorController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Api\Admin\ServiceController as AdminServiceController;



Route::post('/testEmail', [DashboardController::class, 'testEmail'])->name('testEmail');
Route::post('/generateFacture', [WithdrawController::class, 'generate'])->name('generate');
Route::post('/contrat', [ServiceController::class, 'contrat'])->name('contrat');


//Verification Email
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'emailVerify'])->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'verificationNotification'])->middleware(['throttle:6,1'])->name('verification.send');

//Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/mail-contact', [AuthController::class, 'mailContact']);

Route::post('/password/send-otp', [UserController::class, 'sendOtpCode']);
Route::post('/password/verify-otp', [UserController::class, 'verifyOtpCode']);
Route::post('/password/reset', [UserController::class, 'updatePassword']);

// TrainingModule Routes
Route::get('/training-modules', [TrainingModuleController::class, 'index']);

// Service Routes
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/categories', [ServiceController::class, 'listCategory']);

Route::get('/meeting-points/search', [MeetingPointController::class, 'get_meeting_points']);

Route::middleware('auth:sanctum')->group(function () {

            //Auth
            Route::post('/passTest', [AuthController::class, 'checkAsk']);
            Route::get('/export', [WithdrawController::class, 'export'])->name('export');


    Route::middleware(['admin'])->group(function () {
        Route::prefix('admin')->group(function () {

            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.stat');
            Route::get('/graph', [DashboardController::class, 'graph'])->name('admin.graph');
            Route::get('/withdrawalYear', [DashboardController::class, 'withdrawalYear'])->name('admin.withdrawalYear');

            // Category Service
            Route::get('/listCategoryService', [AdminServiceController::class, 'listCategoryService'])->name('admin.listCategoryService');
            Route::post('/addCategoryService', [AdminServiceController::class, 'addCategoryService'])->name('admin.addCategoryService');
            Route::put('/updateCategoryService/{categoryService}', [AdminServiceController::class, 'updateCategoryService'])->name('admin.updateCategoryService');
            Route::delete('/deleteCategoryService/{categoryService}', [AdminServiceController::class, 'deleteCategoryService'])->name('admin.deleteCategoryService');
            // Service
            Route::get('/listService', [AdminServiceController::class, 'listService'])->name('admin.listService');
            Route::get('/listServiceByCategory/{categoryService}', [AdminServiceController::class, 'listServiceByCategory'])->name('admin.listServiceByCategory');
            Route::post('/addService', [AdminServiceController::class, 'addService'])->name('admin.addService');
            Route::get('/actionService/{service}', [AdminServiceController::class, 'actionService'])->name('admin.actionService');
            Route::put('/updateService/{service}', [AdminServiceController::class, 'updateService'])->name('admin.updateService');
            // ServiceItem
            Route::get('/listserviceItem/{service}', [AdminServiceController::class, 'listserviceItem'])->name('admin.listserviceItem');
            Route::post('/addServiceItem/{service}', [AdminServiceController::class, 'addServiceItem'])->name('admin.addServiceItem');
            Route::put('/updateServiceItem/{serviceItem}', [AdminServiceController::class, 'updateServiceItem'])->name('admin.updateServiceItem');
            Route::delete('/deleteServiceItem/{serviceItem}', [AdminServiceController::class, 'deleteServiceItem'])->name('admin.deleteServiceItem');

            // Code Access
            Route::get('/learner/codeacess/list', [AdminServiceController::class, 'LearnCodeAccess'])->name('admin.LearnCodeAccess');
            Route::get('/codeacess/list', [AdminServiceController::class, 'ListAccessCode'])->name('admin.listaccesscode');
            Route::post('/codeaccess/add', [AdminServiceController::class, 'AddAccessCode'])->name('admin.addAccessCode');
            Route::delete('/codeaccess/delete/{codeacess}', [AdminServiceController::class, 'DeleteAccessCode'])->name('admin.deleteAccessCode');

            // Admin
            Route::get('/', [AdminController::class, 'index'])->name('admin');
            Route::put('/{user}/action', [AdminController::class, 'action'])->name('admin.action');
            Route::post('/addAdmin', [AdminController::class, 'addAdmin'])->name('admin.addAdmin');
            Route::delete('/{user}/deleteAdmin', [AdminController::class, 'deleteAdmin'])->name('admin.deleteAdmin');
            Route::get('/withdraws', [AdminController::class, 'withdraws'])->name('admin.withdraws');
            Route::get('/withdraws/{withdraw}/show', [AdminController::class, 'showWthdraw']);
            Route::post('/withdraws/{withdraw}/approve', [AdminController::class, 'approve']);
            Route::post('/withdraws/{withdraw}/decline', [AdminController::class, 'decline']);


           // Learners
            Route::get('/learners', [AdminLearnerController::class, 'index'])->name('admin.learners');
            Route::put('/learners/{user}/action', [AdminLearnerController::class, 'action'])->name('admin.learners.action');
            Route::get('/learners/{user}/show', [AdminLearnerController::class, 'show'])->name('admin.learners.show');
            Route::get('/learners/{user}/userBadges', [AdminLearnerController::class, 'userBadges'])->name('admin.learners.userBadges');
            Route::get('/learners/{user}/userProgress', [AdminLearnerController::class, 'userProgress'])->name('admin.learners.userProgress');
            Route::get('/learners/{user}/lessonLearner', [AdminLearnerController::class, 'lessonLearner'])->name('admin.learners.lessonLearner');
            Route::get('/learners/{user}/mySubscribe', [AdminLearnerController::class, 'mySubscribe'])->name('admin.learners.mySubscribe');
            Route::get('/learners/{user}/listContrat', [AdminLearnerController::class, 'listcontract'])->name('admin.learners.listContrat');
            Route::post('/learners/{user}/addContrat', [AdminLearnerController::class, 'addcontract'])->name('admin.learners.addContrat');
            Route::post('/learners/{contract}/updateContact', [AdminLearnerController::class, 'updatecontract'])->name('admin.learners.updatecontract'); 
            Route::get('/learners/list/examen', [AdminLearnerController::class, 'ListLearnerToExam'])->name('admin.examen.list');
            Route::post('/learners/add/examen', [AdminLearnerController::class, 'addLearnerToExam'])->name('admin.examen.addd');
            Route::put('/learners/update/examen/{examen}', [AdminLearnerController::class, 'updateLearnerToExam'])->name('admin.examen.update');
            Route::delete('/learners/delete/examen/{examen}', [AdminLearnerController::class, 'deleteLearnerToExam'])->name('admin.examen.delete');

            // Monitors
            Route::get('/monitors', [MonitorController::class, 'index'])->name('admin.monitors');
            Route::put('/monitors/{user}/action', [MonitorController::class, 'action'])->name('admin.monitors.action');
            Route::get('/monitors/{user}/show', [MonitorController::class, 'show'])->name('admin.monitors.show');
            Route::get('/monitors/{user}/listVehicules', [MonitorController::class, 'listVehicules'])->name('admin.monitors.listVehicules');
            Route::get('/monitors/{user}/listMeetingPoint', [MonitorController::class, 'listMeetingPoint'])->name('admin.monitors.listMeetingPoint');
            Route::get('/monitors/{user}/listAvailabilities', [MonitorController::class, 'listAvailabilities'])->name('admin.monitors.listAvailabilities');
            Route::get('/monitors/{user}/listAvailabilitiesRepeat', [MonitorController::class, 'listAvailabilitiesRepeat'])->name('admin.monitors.listAvailabilitiesRepeat');
            Route::get('/monitors/{user}/listLearner', [MonitorController::class, 'listLearner'])->name('admin.monitors.listLearner');
            Route::get('/monitors/{user}/listAppointment', [MonitorController::class, 'listAppointment'])->name('admin.monitors.listAppointment');
            Route::post('/monitors/{user}/createAppointment', [MonitorController::class, 'createAppointment'])->name('admin.monitors.createAppointment');
            Route::put('/monitors/hourDiscount', [MonitorController::class, 'updateHourDiscountMonitor'])->name('admin.monitors.hourDiscount');
            Route::put('/monitors/hourPrice', [MonitorController::class, 'updateHourAmmountMonitor'])->name('admin.monitors.hourAmmount');

            // Appointment
            Route::get('/appointment', [AdminAppointmentController::class, 'index'])->name('admin.appointment');
            Route::post('/appointment/create', [AdminAppointmentController::class, 'createAppointment'])->name('admin.appointment.create');
            Route::put('/appointment/{appointment}/cancel', [AdminAppointmentController::class, 'cancel'])->name('admin.appointment.cancel');
            Route::post('/appointment/{appointment}/sendmail', [AdminAppointmentController::class, 'sendmail'])->name('admin.appointment.sendmail');

        });
    });

    // Learner Routes
    Route::get('/userBadges', [LearnerController::class, 'userBadges']);
    Route::get('learner/badges/qty', [LearnerController::class, 'userBadgesQty']);
    Route::get('/userProgress', [LearnerController::class, 'userProgress']);
    Route::get('/lessonLearner', [LearnerController::class, 'lessonLearner']);
    Route::post('/learner/cancel/rdv', [LearnerController::class, 'cancelrRdv']);
    Route::post('/learner/display/availabilities', [LearnerController::class, 'instructorsAvailable']);
    Route::get('/list/examen/learner/{learner_id}', [LearnerController::class, 'ListExamRdv']);
    Route::get('/learners/code/access/{learner_id}', [LearnerController::class, 'learnercodeaccess'])->name('admin.examen.codeaccess');
    // Notifications Routes
    Route::get('/notif/allAsRead', [NotificationController::class, 'allAsRead']);
    Route::get('/userNotification', [NotificationController::class, 'userNotification']);
    Route::get('/notif/{notification}/oneAsRead', [NotificationController::class, 'oneAsRead']);

    // Module Routes
    Route::get('/modules/{user}/module', [ModuleController::class, 'index']);
    Route::post('/modules/{appointment}/store', [ModuleController::class, 'store']);

    // Dashboard Monitor
    Route::get('/dashboard/stat', [DashboardMonitorController::class, 'stat']);
    Route::get('/dashboard/listLearner', [DashboardMonitorController::class, 'listLearner']);
    Route::get('/dashboard/lists', [DashboardMonitorController::class, 'lists']);
    Route::get('/dashboard/graph', [DashboardMonitorController::class, 'graph']);

    // Update profile routes
    Route::put('/profile/update/learner', [UserController::class, 'updateLearnerProfile']);
    Route::post('/profile/update/doclearner', [UserController::class, 'updateDocLearnerProfile']);
    Route::put('/profile/update/instructor', [UserController::class, 'updateInstructorProfile']);
    Route::put('/profile/update/admin', [UserController::class, 'updateAdminProfile']);
    Route::put('/profile/update/password', [UserController::class, 'changePassword']);
    Route::post('/profile/update/photo', [UserController::class, 'updateImage']);
    Route::put('/profile/update/dropPhoto', [UserController::class, 'dropImage']);
    Route::put('/first_login_planning', [UserController::class, 'first_login_planning']);
    Route::put('/first_login_dashboard', [UserController::class, 'first_login_dashboard']);
    Route::put('/profile/deleteCompte', [UserController::class, 'deleteCompte']);

    // View profile routes
    Route::get('/profile/learner', [UserController::class, 'viewLearnerProfile']);
    Route::get('/profile/instructor', [UserController::class, 'viewInstructorProfile']);
    Route::get('/profile/admin', [UserController::class, 'viewAdminProfile']);
    Route::get('/list/examen/monitor/{monitor_id}', [UserController::class, 'ListExamRdv']);
    Route::put('/mark/examen/monitor', [UserController::class, 'markExamRdv']);

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
    Route::post('/vehicles/{vehicle}', [VehicleController::class, 'update']);
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy']);

    // Availability Routes
    Route::post('/availabilities', [AvailabilityController::class, 'store']);
    Route::get('/availabilities', [AvailabilityController::class, 'index']);
    Route::get('/availabilities/unbooked', [AvailabilityController::class, 'getUnbookedAvailabilities']);
    Route::get('/listByDate', [AvailabilityController::class, 'listByDate']);
    Route::get('/availabilities/{availability}', [AvailabilityController::class, 'show']);
    Route::put('/availabilities/{availability}', [AvailabilityController::class, 'update']);
    Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy']);

    // AvailabilityRepeated Routes
    Route::get('/availability-repeateds', [AvailabilityRepeatedController::class, 'index']);
    Route::post('/availability-repeateds', [AvailabilityRepeatedController::class, 'store']);
    Route::get('/availability-repeateds/{availabilityRepeated}', [AvailabilityRepeatedController::class, 'show']);
    Route::put('/availability-repeateds/{availabilityRepeated}', [AvailabilityRepeatedController::class, 'update']);
    Route::delete('/availability-repeateds/{day}', [AvailabilityRepeatedController::class, 'destroy']);

    // Appointment Routes
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::post('/appointments/propose', [AppointmentController::class, 'propose']);
    Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
    Route::post('/appointments/{appointment}/confirme', [AppointmentController::class, 'confirme']);
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
    Route::post('/appointments/{appointment}/presence', [AppointmentController::class, 'markPresence']);
    Route::post('/appointments/{appointment}/absence', [AppointmentController::class, 'markAbsence']);
    Route::post('/appointments/{appointment}/finished', [AppointmentController::class, 'markFinished']);
    Route::get('/appointments/{user}/instructor', [AppointmentController::class, 'listLearner']);
    Route::get('/appointments/lists', [AppointmentController::class, 'lists']);
    Route::get('/appointments/{user}/lessons', [AppointmentController::class, 'lessonLearner']);
    Route::get('/appointments/{user}/comments', [AppointmentController::class, 'comments']);
    Route::post('/appointments/addComment', [AppointmentController::class, 'addComment']);
    Route::put('/appointments/{note}/updateComment', [AppointmentController::class, 'updateComment']);

    // Monitor specific routes
    Route::get('/monitor/check-first-appointment/{learner}', [AppointmentController::class, 'checkFirstAppointment']);
    Route::post('/monitor/reset-learner-test', [AppointmentController::class, 'resetLearnerTest']);

    // UserDoc Routes
    Route::post('/user-docs', [UserDocController::class, 'store']);
    Route::post('/info-docs', [UserDocController::class, 'save_doc']);
    Route::get('/user-docs', [UserDocController::class, 'index']);
    Route::delete('/user-docs/{userDoc}', [UserDocController::class, 'destroy']);
    

    // BankAccount Routes
    Route::post('/bank-accounts', [BankAccountController::class, 'store']);
    Route::get('/bank-accounts', [BankAccountController::class, 'index']);
    Route::get('/bank-accounts/{bankAccount}', [BankAccountController::class, 'show']);
    Route::put('/bank-accounts/{bankAccount}', [BankAccountController::class, 'update']);
    Route::delete('/bank-accounts/{bankAccount}', [BankAccountController::class, 'destroy']);

    // Withdraw Routes
    Route::get('/withdraws/stat', [WithdrawController::class, 'stat']);
    Route::get('/withdraws/no_billable', [WithdrawController::class, 'no_billable']);
    Route::post('/withdraws', [WithdrawController::class, 'store']);
    Route::get('/withdraws/list_monitor', [WithdrawController::class, 'list']);

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
    Route::post('/services/makeSubscribe', [ServiceController::class, 'makeSubscribe']);
    Route::post('/services/makeSubscribeWithLearner', [ServiceController::class, 'makeSubscribeWithLearnerId']);
    Route::get('/services/mySubscrube/{user}', [ServiceController::class, 'mySubscribe']);
    Route::get('/services/packCode', [ServiceController::class, 'packCode']);
    Route::get('/services/contrat', [ServiceController::class, 'listContrat']);
    Route::get('/services/info', [ServiceController::class, 'infoSubscribe']);

    // SubscriptionRegistration Routes
    Route::post('/subscriptions', [SubscriptionRegistrationController::class, 'store']);
    Route::post('/payments/{payment}/record', [SubscriptionRegistrationController::class, 'recordPayment']);
    Route::post('/contracts/{contract}/update', [SubscriptionRegistrationController::class, 'updateContract']);
    Route::get('/subscriptions', [SubscriptionRegistrationController::class, 'index']);
    Route::post('/subscriptions/{subscription}/deactivate', [SubscriptionRegistrationController::class, 'deactivate']);
    Route::post('/subscriptions/{subscription}/reactivate', [SubscriptionRegistrationController::class, 'reactivate']);

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

    // Paiement
    Route::post('/create-payment-intent', [StripeController::class, 'createPaymentIntent']);
    Route::post('/create-payment-intents', [StripeController::class, 'createPaymentIntent']);
});


