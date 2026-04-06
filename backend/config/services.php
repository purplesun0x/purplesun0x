<?php

return [
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    'referrals' => [
        'commission_percentage' => env('REFERRAL_COMMISSION_PERCENT', 5),
    ],
];
