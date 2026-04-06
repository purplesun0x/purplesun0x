<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ReferralService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(private readonly ReferralService $referralService)
    {
    }

    public function stripe(Request $request)
    {
        // In production: verify Stripe signature header before processing payload.
        $payload = $request->all();
        if (($payload['type'] ?? null) === 'checkout.session.completed') {
            $orderId = $payload['data']['object']['metadata']['order_id'] ?? null;
            if ($orderId) {
                $order = Order::query()->find($orderId);
                if ($order && $order->payment_status !== 'paid') {
                    $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                    $this->referralService->processCommissionForOrder($order);
                }
            }
        }

        return response()->json(['received' => true]);
    }
}
