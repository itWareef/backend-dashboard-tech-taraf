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
    Route::post('/password/send-otp', [PasswordOtpController::class, 'sendOtp']);
    Route::post('/password/verify-otp', [PasswordOtpController::class, 'verifyOtpAndReset']);

});
