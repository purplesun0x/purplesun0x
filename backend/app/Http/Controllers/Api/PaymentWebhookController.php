<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\LicenseService;
use App\Services\ReferralService;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function paystack(Request $request, LicenseService $licenseService, ReferralService $referralService)
    {
        $reference = data_get($request->all(), 'data.reference');
        return $this->markPaidIfExists($reference, $licenseService, $referralService);
    }

    public function flutterwave(Request $request, LicenseService $licenseService, ReferralService $referralService)
    {
        $reference = data_get($request->all(), 'data.tx_ref');
        return $this->markPaidIfExists($reference, $licenseService, $referralService);
    }

    private function markPaidIfExists(?string $reference, LicenseService $licenseService, ReferralService $referralService)
    {
        if (!$reference) {
            return response()->json(['message' => 'Missing reference'], 422);
        }

        $order = Order::where('order_number', $reference)->orWhere('provider_reference', $reference)->with('items')->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status !== 'paid') {
            $order->update(['status' => 'paid', 'paid_at' => now()]);
            foreach ($order->items as $item) {
                $licenseService->generateForOrderItem($item, $order->user_id);
            }
            $referralService->rewardIfEligible($order);
        }

        return response()->json(['message' => 'Webhook processed']);
    }
}
