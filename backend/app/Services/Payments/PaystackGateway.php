<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class PaystackGateway implements PaymentGatewayInterface
{
    public function initialize(Order $order, string $callbackUrl): array
    {
        $response = Http::withToken(config('services.paystack.secret'))
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $order->user->email,
                'amount' => (int) ($order->total * 100),
                'reference' => $order->order_number,
                'callback_url' => $callbackUrl,
            ])->json();

        return [
            'provider' => 'paystack',
            'authorization_url' => $response['data']['authorization_url'] ?? null,
            'reference' => $response['data']['reference'] ?? $order->order_number,
            'raw' => $response,
        ];
    }

    public function verify(string $reference): array
    {
        return Http::withToken(config('services.paystack.secret'))
            ->get("https://api.paystack.co/transaction/verify/{$reference}")
            ->json();
    }
}
