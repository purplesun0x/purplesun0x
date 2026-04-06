<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function stripeWebhook(Request $request): JsonResponse
    {
        // Validate Stripe signature with STRIPE_WEBHOOK_SECRET in production.
        $payload = $request->all();
        $orderId = $payload['data']['object']['metadata']['order_id'] ?? null;

        if ($orderId) {
            Order::where('id', $orderId)->update(['payment_status' => 'paid', 'status' => 'processing']);
        }

        return response()->json(['received' => true]);
    }

    public function paystackWebhook(Request $request): JsonResponse
    {
        // Validate Paystack signature with PAYSTACK_SECRET_KEY in production.
        $payload = $request->all();
        $orderId = $payload['data']['metadata']['order_id'] ?? null;

        if ($orderId) {
            Order::where('id', $orderId)->update(['payment_status' => 'paid', 'status' => 'processing']);
        }

        return response()->json(['received' => true]);
    }
}
