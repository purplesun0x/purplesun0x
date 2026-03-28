<?php
require_once __DIR__ . '/ManualGateway.php';
require_once __DIR__ . '/PaystackGateway.php';

function availableGatewayKeys(): array
{
    $keys = ['manual'];
    if ((string)getenv('PAYSTACK_SECRET_KEY') !== '') {
        $keys[] = 'paystack';
    }

    return $keys;
}

function makeGateway(string $key): PaymentGatewayInterface
{
    return match ($key) {
        'paystack' => new PaystackGateway((string)getenv('PAYSTACK_SECRET_KEY')),
        default => new ManualGateway(),
    };
}
