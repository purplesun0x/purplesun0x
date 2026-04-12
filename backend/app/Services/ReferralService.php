<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Referral;
use App\Models\User;

class ReferralService
{
    public function rewardIfEligible(Order $order): void
    {
        $buyer = User::find($order->user_id);

        if (!$buyer?->referred_by_user_id) {
            return;
        }

        $rate = 5.00;
        $amount = round(($order->total * $rate) / 100, 2);

        Referral::create([
            'referrer_user_id' => $buyer->referred_by_user_id,
            'referred_user_id' => $buyer->id,
            'order_id' => $order->id,
            'commission_rate' => $rate,
            'commission_amount' => $amount,
            'status' => 'approved',
        ]);

        User::whereKey($buyer->referred_by_user_id)->increment('commission_balance', $amount);
    }
}
