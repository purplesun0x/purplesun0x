<?php

namespace App\Services\Payments;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function initialize(Order $order, string $callbackUrl): array;
    public function verify(string $reference): array;
}
