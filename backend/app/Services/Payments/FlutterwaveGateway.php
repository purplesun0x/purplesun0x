<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class FlutterwaveGateway implements PaymentGatewayInterface
{
    public function initialize(Order $order, string $callbackUrl): array
    {
        $payload = [
            'tx_ref' => $order->order_number,
            'amount' => (float) $order->total,
            'currency' => 'USD',
            'redirect_url' => $callbackUrl,
            'customer' => ['email' => $order->user->email],
            'customizations' => ['title' => 'Script Marketplace'],
        ];

        $response = Http::withToken(config('services.flutterwave.secret'))
            ->post('https://api.flutterwave.com/v3/payments', $payload)
            ->json();

        return [
            'provider' => 'flutterwave',
            'authorization_url' => $response['data']['link'] ?? null,
            'reference' => $order->order_number,
            'raw' => $response,
        ];
    }

    public function verify(string $reference): array
    {
        return Http::withToken(config('services.flutterwave.secret'))
            ->get('https://api.flutterwave.com/v3/transactions/verify_by_reference', ['tx_ref' => $reference])
            ->json();
    }
}
