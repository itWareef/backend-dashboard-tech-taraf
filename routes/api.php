<?php

use App\Http\Controllers\Api\CustomerController\CustomerController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Auth\PasswordOtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::prefix('otp')->group(function () {
    Route::post('send', [OtpController::class, 'send']);
    Route::post('verify', [OtpController::class, 'verify']);
});

Route::prefix('customer')->group(function () {
    Route::post('login', [CustomerController::class, 'login']);
    Route::post('register', [CustomerController::class, 'register']);
    Route::post('verify-otp', [CustomerController::class, 'verifyOtp']);
    Route::post('logout', [CustomerController::class, 'logout'])->middleware(['auth:customer',]);
    Route::post('update-profile', [CustomerController::class, 'updateProfile'])->middleware(['auth:customer',]);
    Route::get('me', [CustomerController::class, 'me'])->middleware(['auth:customer',]);
    Route::post('/password/send-otp', [PasswordOtpController::class, 'sendOtp']);
    Route::post('/password/verify-otp', [PasswordOtpController::class, 'verifyOtpAndReset']);
    Route::post('maintenance-request', [\App\Http\Controllers\MaintenanceRequestController::class ,'store'])->middleware(['auth:customer',]);
    Route::post('maintenance-request/add-review/{maintenanceRequest}', [\App\Http\Controllers\MaintenanceRequestController::class ,'addReview'])->middleware(['auth:customer',]);
    Route::get('maintenance-request/{status}', [\App\Http\Controllers\MaintenanceRequestController::class ,'list'])->middleware(['auth:customer',]);
    Route::post('planting-request', [\App\Http\Controllers\PlantingRequestController::class ,'store'])->middleware(['auth:customer',]);
    Route::post('planting-request/add-review/{plantingRequest}', [\App\Http\Controllers\PlantingRequestController::class ,'addReview'])->middleware(['auth:customer',]);
    Route::get('planting-request/{status}', [\App\Http\Controllers\PlantingRequestController::class ,'list'])->middleware(['auth:customer',]);
    Route::get('units', [\App\Http\Controllers\UnitController::class ,'listForCustomer'])->middleware(['auth:customer',]);
    Route::get('projects', [\App\Http\Controllers\UnitController::class ,'listProjectsForCustomer'])->middleware(['auth:customer',]);
});

Route::prefix('supervisors')->group(function () {
    Route::post('login', [SupervisorController::class, 'login']);
    Route::post('register', [SupervisorController::class, 'register']);
    Route::post('verify-otp', [SupervisorController::class, 'verifyOtp']);
    Route::post('logout', [SupervisorController::class, 'logout'])->middleware(['auth:supervisor',]);
    Route::post('update-profile', [SupervisorController::class, 'updateProfile'])->middleware(['auth:supervisor',]);
    Route::get('me', [SupervisorController::class, 'me'])->middleware(['auth:supervisor',]);
    Route::post('/password/send-otp', [PasswordOtpController::class, 'sendOtpSuperVisors']);
    Route::post('/password/verify-otp', [PasswordOtpController::class, 'verifyOtpAndReset']);
    Route::get('list-requests', [SupervisorController::class, 'requests'])->middleware(['auth:supervisor',]);
    Route::get('list-requests-in-progress', [SupervisorController::class, 'requestsInProgress'])->middleware(['auth:supervisor',]);
    Route::get('list-requests-finished', [SupervisorController::class, 'requestsFinished'])->middleware(['auth:supervisor',]);
    Route::middleware('auth:supervisor')->group(function () {
        Route::post('{id}/accept-or-reject', [SupervisorController::class, 'acceptOrReject']);
        Route::post('{id}/finish', [SupervisorController::class, 'finishedRequest']);
        Route::post('{id}/another-visit', [SupervisorController::class, 'anotherVisit']);
    });
});
