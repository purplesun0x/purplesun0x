<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\LicenseService;
use App\Services\Payments\FlutterwaveGateway;
use App\Services\Payments\MockGateway;
use App\Services\Payments\PaystackGateway;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function checkout(Request $request, PaystackGateway $paystack, FlutterwaveGateway $flutterwave, MockGateway $mock)
    {
        $payload = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
            'provider' => 'required|in:paystack,flutterwave,mock',
        ]);

        $products = Product::whereIn('id', $payload['product_ids'])->get();
        $subtotal = $products->sum('price');

        $order = Order::create([
            'user_id' => $request->user()->id,
            'order_number' => 'ORD-' . Str::upper(Str::random(12)),
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'status' => 'pending',
            'payment_provider' => $payload['provider'],
        ]);

        foreach ($products as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'unit_price' => $product->price,
                'quantity' => 1,
            ]);
        }

        $gateway = match ($payload['provider']) {
            'paystack' => $paystack,
            'flutterwave' => $flutterwave,
            default => $mock,
        };

        $init = $gateway->initialize($order->load('user'), config('app.frontend_url') . '/payment/callback');

        $order->update(['provider_reference' => $init['reference']]);

        return response()->json(['order' => $order, 'payment' => $init], 201);
    }

    public function verify(Request $request, PaystackGateway $paystack, FlutterwaveGateway $flutterwave, MockGateway $mock, LicenseService $licenseService, ReferralService $referralService)
    {
        $payload = $request->validate([
            'order_number' => 'required|exists:orders,order_number',
        ]);

        $order = Order::where('order_number', $payload['order_number'])->with('items.product')->firstOrFail();

        $gateway = match ($order->payment_provider) {
            'paystack' => $paystack,
            'flutterwave' => $flutterwave,
            default => $mock,
        };

        $verification = $gateway->verify($order->provider_reference ?? $order->order_number);
        $status = data_get($verification, 'data.status') === 'success' ? 'paid' : 'failed';

        $order->update([
            'status' => $status,
            'paid_at' => $status === 'paid' ? now() : null,
        ]);

        if ($status === 'paid') {
            foreach ($order->items as $item) {
                $licenseService->generateForOrderItem($item, $order->user_id);
            }
            $referralService->rewardIfEligible($order);
        }

        return response()->json(['order' => $order->fresh('items.license')]);
    }
}
