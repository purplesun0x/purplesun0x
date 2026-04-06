<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function link(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'referral_code' => $user->referral_code,
            'referral_link' => config('app.frontend_url') . '/register?ref=' . $user->referral_code,
        ]);
    }

    public function stats(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'total_referrals' => $user->referrals()->count(),
            'confirmed_referrals' => $user->referrals()->where('status', 'confirmed')->count(),
            'referral_earnings' => $user->referrals()->where('status', 'confirmed')->sum('commission'),
            'wallet_balance' => $user->wallet_balance,
        ]);
    }

    public function withdraw(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $user = $request->user();
        abort_if($data['amount'] > $user->wallet_balance, 422, 'Insufficient wallet balance');

        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'status' => 'pending',
        ]);

        return response()->json($withdrawal, 201);
    }
}
