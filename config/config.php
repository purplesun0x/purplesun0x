<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Script Marketplace',
        'base_url' => 'http://localhost/purplesun0x/public',
        'session_name' => 'script_marketplace_session',
    ],
    'security' => [
        'allowed_extensions' => ['zip', 'rar', 'pdf'],
        'max_upload_size' => 25 * 1024 * 1024,
    ],
    'paystack' => [
        'public_key' => 'pk_test_replace_me',
        'secret_key' => 'sk_test_replace_me',
        'initialize_url' => 'https://api.paystack.co/transaction/initialize',
        'verify_url' => 'https://api.paystack.co/transaction/verify/',
    ],
    'flutterwave' => [
        'public_key' => 'FLWPUBK_TEST_replace_me',
        'secret_key' => 'FLWSECK_TEST_replace_me',
        'initialize_url' => 'https://api.flutterwave.com/v3/payments',
        'verify_url' => 'https://api.flutterwave.com/v3/transactions/',
    ],
];
