<?php

namespace App\Services\Payments;

use App\Models\Order;

class MockGateway implements PaymentGatewayInterface
{
    public function initialize(Order $order, string $callbackUrl): array
    {
        return [
            'provider' => 'mock',
            'authorization_url' => $callbackUrl . '?reference=' . $order->order_number,
            'reference' => $order->order_number,
            'raw' => ['status' => 'success'],
        ];
    }

    public function verify(string $reference): array
    {
        return [
            'status' => true,
            'data' => [
                'status' => 'success',
                'reference' => $reference,
                'amount' => 0,
            ],
        ];
    }
}
