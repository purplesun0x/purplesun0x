<?php
require_once __DIR__ . '/PaymentGatewayInterface.php';

class PaystackGateway implements PaymentGatewayInterface
{
    public function __construct(private string $secretKey)
    {
    }

    public function key(): string
    {
        return 'paystack';
    }

    public function initialize(array $payload): array
    {
        if ($this->secretKey === '') {
            return ['ok' => false, 'error' => 'Missing PAYSTACK_SECRET_KEY environment variable.'];
        }

        $amountKobo = (int)round($payload['amount'] * 100);

        $requestBody = [
            'email' => $payload['email'],
            'amount' => $amountKobo,
            'currency' => 'NGN',
            'callback_url' => $payload['callback_url'],
            'metadata' => [
                'session_token' => $payload['session_token'],
            ],
        ];

        $response = $this->sendRequest('POST', 'https://api.paystack.co/transaction/initialize', $requestBody);
        if (!$response['ok']) {
            return $response;
        }

        if (empty($response['raw']['status']) || empty($response['raw']['data']['authorization_url']) || empty($response['raw']['data']['reference'])) {
            return ['ok' => false, 'error' => 'Invalid initialize response from Paystack.', 'raw' => $response['raw']];
        }

        return [
            'ok' => true,
            'redirect_url' => $response['raw']['data']['authorization_url'],
            'reference' => $response['raw']['data']['reference'],
            'raw' => $response['raw'],
        ];
    }

    public function verify(string $reference): array
    {
        if ($this->secretKey === '') {
            return ['ok' => false, 'error' => 'Missing PAYSTACK_SECRET_KEY environment variable.'];
        }

        $response = $this->sendRequest('GET', 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference));
        if (!$response['ok']) {
            return $response;
        }

        $data = $response['raw']['data'] ?? null;
        $isSuccess = ($response['raw']['status'] ?? false) === true
            && is_array($data)
            && ($data['status'] ?? '') === 'success';

        if (!$isSuccess) {
            return ['ok' => false, 'error' => 'Paystack transaction is not successful.', 'raw' => $response['raw']];
        }

        return [
            'ok' => true,
            'reference' => $data['reference'] ?? $reference,
            'raw' => $response['raw'],
        ];
    }

    private function sendRequest(string $method, string $url, ?array $body = null): array
    {
        $ch = curl_init();
        $headers = [
            'Authorization: Bearer ' . $this->secretKey,
            'Content-Type: application/json',
        ];

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
        ]);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $rawResponse = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($rawResponse === false) {
            return ['ok' => false, 'error' => 'Paystack request failed: ' . $error];
        }

        $decoded = json_decode($rawResponse, true);
        if (!is_array($decoded)) {
            return ['ok' => false, 'error' => 'Could not decode Paystack response JSON.'];
        }

        if ($httpCode >= 400) {
            return ['ok' => false, 'error' => 'Paystack returned HTTP ' . $httpCode, 'raw' => $decoded];
        }

        return ['ok' => true, 'raw' => $decoded];
    }
}
