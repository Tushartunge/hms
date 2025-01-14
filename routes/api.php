<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\MedicalTestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WaitingRoomController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HealthRecordController;
//use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

// routes/web.php
Route::post('/login', [AuthController::class, 'login']);


// Role routes

Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

// Permission routes
Route::get('/permissions', [PermissionController::class, 'index']);
Route::post('/roles/{roleId}/permissions', [PermissionController::class, 'assignPermissions']);

// Appointment Routes
Route::resource('appointments', AppointmentController::class);

// Billing Routes
Route::resource('billings', BillingController::class);

// routes/api.php

    Route::resource('doctors', DoctorController::class);
    
// waiting room route
Route::resource('waitingrooms', WaitingRoomController::class);


// Patient Routes
Route::resource('patients', PatientController::class);

// Prescription Routes
Route::resource('prescriptions', PrescriptionController::class);

// Medical Test Routes
Route::resource('medical-tests', MedicalTestController::class);

// User Routes
//Route::resource('users', UserController::class);

// Transaction Routes
Route::resource('transactions', TransactionController::class);

// Document Routes
Route::resource('documents', DocumentController::class);

// Role and Permission Routes
// Route::resource('roles', RoleController::class);
// Route::resource('permissions', RoleController::class);

// // Notification Routes
// Route::resource('notifications', NotificationController::class);


// rout for changing status as in consulting 
//Route::put('waiting-room/{waitingRoomId}/start-consultation', [WaitingRoomController::class, 'startConsultation']);

// rout for changing status as completed 
Route::patch('waiting-rooms/{waitingRoomId}/complete', [WaitingRoomController::class, 'completeConsultation']);


Route::prefix('patients/{patientId}/health-records')->group(function () {
    Route::get('/', [HealthRecordController::class, 'getHealthRecords']);
    Route::post('/', [HealthRecordController::class, 'createHealthRecord']);
    Route::get('/history', [HealthRecordController::class, 'getPatientHistory']);
    Route::post('/clinical-note', [HealthRecordController::class, 'addClinicalNote']);
    Route::post('/upload-attachment', [HealthRecordController::class, 'uploadAttachment']);
});

Route::prefix('health-records')->group(function () {
    Route::put('/{recordId}', [HealthRecordController::class, 'updateHealthRecord']);
    Route::delete('/{recordId}', [HealthRecordController::class, 'deleteHealthRecord']);
    Route::post('/finding', [HealthRecordController::class, 'addFinding']);
});


Route::get('/example', [ApiController::class, 'exampleMethod']);
Route::post('/submit', [ApiController::class, 'submitMethod']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
