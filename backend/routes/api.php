<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\DownloadController;
use App\Http\Controllers\Api\PaymentWebhookController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product:slug}', [ProductController::class, 'show']);

Route::post('/payments/webhook/paystack', [PaymentWebhookController::class, 'paystack']);
Route::post('/payments/webhook/flutterwave', [PaymentWebhookController::class, 'flutterwave']);

Route::middleware('auth:api')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard']);

    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    Route::post('/checkout/verify', [CheckoutController::class, 'verify']);

    Route::get('/downloads', [DownloadController::class, 'index']);
    Route::post('/downloads/{orderItem}/token', [DownloadController::class, 'generateToken']);
    Route::get('/downloads/file/{token}', [DownloadController::class, 'download']);

    Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);

    Route::get('/referrals', [ReferralController::class, 'index']);
    Route::get('/referrals/link', [ReferralController::class, 'link']);
});

Route::prefix('admin')->middleware(['auth:admin', 'admin.role:super_admin,manager'])->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/orders', [AdminController::class, 'orders']);
    Route::post('/admins/{admin}/roles', [AdminController::class, 'assignRoles']);
});
