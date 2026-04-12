<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Referral::where('referrer_user_id', $request->user()->id)->latest()->paginate(20)
        );
    }

    public function link(Request $request)
    {
        return response()->json([
            'referral_link' => config('app.frontend_url') . '/register?ref=' . $request->user()->referral_code,
        ]);
    }
}
