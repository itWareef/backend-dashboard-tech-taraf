<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\ContractRequestController;
use App\Http\Controllers\Api\CustomerController\CustomerController;
use App\Http\Controllers\Api\DeveloperController;
use App\Http\Controllers\Api\GpsLocationController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\Store\AdvertisingPostController;
use App\Http\Controllers\Api\Store\BrandController;
use App\Http\Controllers\Api\Store\CartController;
use App\Http\Controllers\Api\Store\CouponController;
use App\Http\Controllers\Api\Store\FavouriteBrandController;
use App\Http\Controllers\Api\Store\FeatureController;
use App\Http\Controllers\Api\Store\OrderController;
use App\Http\Controllers\Api\Store\SectionController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Auth\PasswordOtpController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// OTP Routes
Route::prefix('otp')->group(function () {
    Route::post('send', [OtpController::class, 'send']);
    Route::post('verify', [OtpController::class, 'verify']);
});

// Customer Routes
Route::prefix('customer')->group(function () {
    // Authentication
    Route::post('login', [CustomerController::class, 'login']);
    Route::post('register', [CustomerController::class, 'register']);
    Route::post('verify-otp', [CustomerController::class, 'verifyOtp']);

    // Profile Management
    Route::middleware('auth:customer')->group(function () {
        Route::post('logout', [CustomerController::class, 'logout']);
        Route::post('update-profile', [CustomerController::class, 'updateProfile']);
        Route::post('update-password', [CustomerController::class, 'updatePassword']);
        Route::get('me', [CustomerController::class, 'me']);
        Route::post('support', [CustomerController::class, 'support']);
    });

    // Password Reset
    Route::post('/password/send-otp', [PasswordOtpController::class, 'sendOtp']);
    Route::post('/password/verify-otp', [PasswordOtpController::class, 'verifyOtpAndReset']);

    // Requests
    Route::middleware('auth:customer')->group(function () {
        // Maintenance Requests
        Route::prefix('maintenance-request')->group(function () {
            Route::post('/', [\App\Http\Controllers\MaintenanceRequestController::class, 'store']);
            Route::post('add-review/{maintenanceRequest}', [\App\Http\Controllers\MaintenanceRequestController::class, 'addReview']);
            Route::get('{status}', [\App\Http\Controllers\MaintenanceRequestController::class, 'list']);
        });

        // Planting Requests
        Route::prefix('planting-request')->group(function () {
            Route::post('/', [\App\Http\Controllers\PlantingRequestController::class, 'store']);
            Route::post('add-review/{plantingRequest}', [\App\Http\Controllers\PlantingRequestController::class, 'addReview']);
            Route::get('{status}', [\App\Http\Controllers\PlantingRequestController::class, 'list']);
        });

        // Other Requests
        Route::post('/contract-requests', [ContractRequestController::class, 'store']);
        Route::post('/services-requests', [\App\Http\Controllers\Api\ServiceRequestController::class, 'store']);
        Route::post('/garden-requests', [\App\Http\Controllers\Api\GardenRequestController::class, 'store']);
        Route::post('/unit-requests', [\App\Http\Controllers\Api\UnitRequestController::class, 'store']);
    });

    // Resources
    Route::middleware('auth:customer')->group(function () {
        Route::get('units', [\App\Http\Controllers\UnitController::class, 'listForCustomer']);
        Route::get('projects', [\App\Http\Controllers\UnitController::class, 'listProjectsForCustomer']);
    });

    Route::get('planting-categories', [\App\Http\Controllers\CategoryController::class, 'listPlanting']);
    Route::get('maintenance-categories', [\App\Http\Controllers\CategoryController::class, 'listMaintenance']);

    // Chat
    Route::post('/chat/send', [ChatController::class, 'send']);

    // Store
    Route::get('brands/list', [BrandController::class, 'list']);
    Route::get('brands/list/{brand}', [BrandController::class, 'show']);
    Route::get('advertising-posts/list/', [AdvertisingPostController::class, 'list']);
    Route::middleware('auth:customer')->prefix('brands/favourites')->group(function () {
        Route::get('/', [FavouriteBrandController::class, 'index']);
        Route::post('/{brand}/toggle', [FavouriteBrandController::class, 'toggle']);
    });
    Route::prefix('cart')->middleware('auth:customer')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::post('/{cartItem}/quantity', [CartController::class, 'updateQuantity']);
        Route::delete('item/{cartItem}', [CartController::class, 'deleteIem']);
        Route::delete('/{cart}', [CartController::class, 'destroy']);
    });

    Route::post('/payment/', [PaymentController::class, 'paymentProcess'])->middleware(['auth:customer']);

    Route::post('coupons/validate', [CouponController::class, 'validateCoupon']);
    Route::post('orders', [OrderController::class, 'store']);
});

// Supervisor Routes
Route::prefix('supervisors')->group(function () {
    // Authentication
    Route::post('login', [SupervisorController::class, 'login']);
    Route::post('register', [SupervisorController::class, 'register']);
    Route::post('verify-otp', [SupervisorController::class, 'verifyOtp']);

    // Profile Management
    Route::middleware('auth:supervisor')->group(function () {
        Route::post('logout', [SupervisorController::class, 'logout']);
        Route::post('update-profile', [SupervisorController::class, 'updateProfile']);
        Route::get('me', [SupervisorController::class, 'me']);
    });

    // Password Reset
    Route::post('/password/send-otp', [PasswordOtpController::class, 'sendOtpSuperVisors']);
    Route::post('/password/verify-otp', [PasswordOtpController::class, 'verifyOtpAndReset']);

    // Requests Management
    Route::middleware('auth:supervisor')->group(function () {
        Route::get('list-requests', [SupervisorController::class, 'requests']);
        Route::get('list-requests-in-progress', [SupervisorController::class, 'requestsInProgress']);
        Route::get('list-requests-finished', [SupervisorController::class, 'requestsFinished']);

        Route::post('{id}/accept-or-reject', [SupervisorController::class, 'acceptOrReject']);
        Route::post('{id}/finish', [SupervisorController::class, 'finishedRequest']);
        Route::post('{id}/another-visit', [SupervisorController::class, 'anotherVisit']);
        Route::post('/gps/update', [GpsLocationController::class, 'update']);
    });
});

// Admin Routes
Route::prefix('admins')->group(function () {
    // Authentication
    Route::post('login', [AdminController::class, 'login']);

    // Profile Management
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AdminController::class, 'logout']);
        Route::post('update-profile', [AdminController::class, 'update']);
        Route::get('me', [AdminController::class, 'me']);
        Route::get('/gps/all', [GpsLocationController::class, 'index']);
    });
    Route::prefix('developers')->group(function () {
        Route::get('/', [DeveloperController::class, 'index']);
        Route::post('/', [DeveloperController::class, 'store']);
        Route::post('{developer}', [DeveloperController::class, 'update']);
        Route::delete('{developer}', [DeveloperController::class, 'destroy']);
    });
    // Store Management
    Route::prefix('store')->group(function () {
        // Features
        Route::prefix('features')->group(function () {
            Route::get('/', [FeatureController::class, 'index']);
            Route::get('list', [FeatureController::class, 'list']);
            Route::post('/', [FeatureController::class, 'store']);
            Route::post('{feature}', [FeatureController::class, 'update']);
            Route::delete('{feature}', [FeatureController::class, 'destroy']);
        });

        // Brands
        Route::prefix('brands')->group(function () {
            Route::get('/', [BrandController::class, 'index']);
            Route::get('list', [BrandController::class, 'list']);
            Route::post('/', [BrandController::class, 'store']);
            Route::post('{brand}', [BrandController::class, 'update']);
            Route::delete('{brand}', [BrandController::class, 'destroy']);
        });

        // Sections
        Route::prefix('sections')->group(function () {
            Route::get('/', [SectionController::class, 'index']);
            Route::get('list', [SectionController::class, 'list']);
            Route::post('/', [SectionController::class, 'store']);
            Route::post('{section}', [SectionController::class, 'update']);
            Route::delete('{section}', [SectionController::class, 'destroy']);
        });

        // Coupons
        Route::prefix('coupons')->group(function () {
            Route::get('/', [CouponController::class, 'index']);
            Route::post('/', [CouponController::class, 'store']);
            Route::post('{coupon}', [CouponController::class, 'update']);
            Route::delete('{coupon}', [CouponController::class, 'destroy']);
            Route::patch('{coupon}/toggle-status', [CouponController::class, 'toggleStatus']);
        });
        // AdvertisingPost
        Route::prefix('advertising-posts')->group(function () {
            Route::get('/', [AdvertisingPostController::class, 'index']);
            Route::get('list', [AdvertisingPostController::class, 'list']);
            Route::post('/', [AdvertisingPostController::class, 'store']);
            Route::post('{advertisingPost}', [AdvertisingPostController::class, 'update']);
            Route::delete('{advertisingPost}', [AdvertisingPostController::class, 'destroy']);
        });
        // Orders
        Route::get('orders', [OrderController::class, 'index']);
    });
});
