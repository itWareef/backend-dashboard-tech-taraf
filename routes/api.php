<?php

use App\Http\Controllers\Api\CustomerController\CustomerController;
use App\Http\Controllers\Api\OtpController;
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
    Route::post('login', [\App\Http\Controllers\Api\SupervisorController::class, 'login']);
    Route::post('register', [\App\Http\Controllers\Api\SupervisorController::class, 'register']);
    Route::post('verify-otp', [\App\Http\Controllers\Api\SupervisorController::class, 'verifyOtp']);
    Route::post('logout', [\App\Http\Controllers\Api\SupervisorController::class, 'logout'])->middleware(['auth:supervisor',]);
    Route::post('update-profile', [\App\Http\Controllers\Api\SupervisorController::class, 'updateProfile'])->middleware(['auth:supervisor',]);
    Route::get('me', [\App\Http\Controllers\Api\SupervisorController::class, 'me'])->middleware(['auth:supervisor',]);
    Route::post('/password/send-otp', [PasswordOtpController::class, 'sendOtpSuperVisors']);
    Route::post('/password/verify-otp', [PasswordOtpController::class, 'verifyOtpAndReset']);
});
