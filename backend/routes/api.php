<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\Admin\ProductManagementController;
use App\Http\Controllers\Api\Admin\AnalyticsController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store'])->middleware('role:super_admin,manager');
    Route::put('/products/{id}', [ProductController::class, 'update'])->middleware('role:super_admin,manager');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware('role:super_admin');

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    Route::get('/referral/link', [ReferralController::class, 'link']);
    Route::get('/referral/stats', [ReferralController::class, 'stats']);
    Route::post('/referral/withdraw', [ReferralController::class, 'withdraw']);

    Route::middleware('role:super_admin,manager,support')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/users', [UserManagementController::class, 'index']);
        Route::post('/products', [ProductManagementController::class, 'store']);
        Route::get('/analytics', [AnalyticsController::class, 'index']);
    });
});

Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook']);
Route::post('/webhooks/paystack', [PaymentController::class, 'paystackWebhook']);
