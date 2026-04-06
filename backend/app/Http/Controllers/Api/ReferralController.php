<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function link(Request $request): JsonResponse
    {
        $code = $request->user()->referral_code;
        return response()->json([
            'code' => $code,
            'link' => config('app.frontend_url') . '/register?ref=' . $code,
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        $referrals = Referral::where('referrer_id', $request->user()->id)->get();

        return response()->json([
            'total_referrals' => $referrals->count(),
            'earnings' => $referrals->sum('commission'),
            'wallet_balance' => $request->user()->wallet_balance,
            'recent' => $referrals->take(10),
        ]);
    }

    public function withdraw(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $user = $request->user();
        if ($validated['amount'] > $user->wallet_balance) {
            return response()->json(['message' => 'Insufficient wallet balance'], 422);
        }

        $user->decrement('wallet_balance', $validated['amount']);

        // In production, persist withdrawal request table for admin approval.
        return response()->json(['message' => 'Withdrawal request submitted']);
    }
}
