<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Referral;
use App\Models\User;

class ReferralService
{
    public function processCommissionForOrder(Order $order): void
    {
        $user = User::query()->find($order->user_id);
        if (!$user || !$user->referred_by || $user->referred_by === $user->id) {
            return;
        }

        $referrer = User::query()->find($user->referred_by);
        if (!$referrer) {
            return;
        }

        $commissionRate = (float) config('services.referrals.commission_percentage', 5);
        $commission = round(((float) $order->total_price) * ($commissionRate / 100), 2);

        Referral::create([
            'referrer_id' => $referrer->id,
            'referred_user_id' => $user->id,
            'commission' => $commission,
            'status' => 'confirmed',
        ]);

        $referrer->increment('wallet_balance', $commission);
    }
}
