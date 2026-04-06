<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ProductManagementController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::post('/webhooks/stripe', [WebhookController::class, 'stripe']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    Route::get('/referral/link', [ReferralController::class, 'link']);
    Route::get('/referral/stats', [ReferralController::class, 'stats']);
    Route::post('/referral/withdraw', [ReferralController::class, 'withdraw']);

    Route::middleware('role:super_admin,manager')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });

    Route::prefix('/admin')->middleware('role:super_admin,manager,support')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/users', [UserManagementController::class, 'index']);
        Route::patch('/users/{user}/status', [UserManagementController::class, 'status']);
        Route::post('/products', [ProductManagementController::class, 'store']);
        Route::get('/analytics', [DashboardController::class, 'analytics']);
    });
});
