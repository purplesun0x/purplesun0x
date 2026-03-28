<?php
require_once __DIR__ . '/PaymentGatewayInterface.php';

class ManualGateway implements PaymentGatewayInterface
{
    public function key(): string
    {
        return 'manual';
    }

    public function initialize(array $payload): array
    {
        return [
            'ok' => true,
            'reference' => 'MANUAL-' . strtoupper(bin2hex(random_bytes(6))),
        ];
    }

    public function verify(string $reference): array
    {
        return [
            'ok' => true,
            'reference' => $reference,
            'raw' => ['manual' => true],
        ];
    }
}
