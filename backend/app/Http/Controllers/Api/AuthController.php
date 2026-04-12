<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $payload = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        $referrerId = null;
        if (!empty($payload['referral_code'])) {
            $referrerId = User::where('referral_code', $payload['referral_code'])->value('id');
        }

        $user = User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => Hash::make($payload['password']),
            'referral_code' => Str::upper(Str::random(8)),
            'referred_by_user_id' => $referrerId,
        ]);

        $token = auth('api')->login($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $token, 'user' => auth('api')->user()]);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'profile' => $user,
            'orders' => $user->orders()->with('items.product')->latest()->get(),
            'licenses' => $user->licenses()->with('product:id,name')->get(),
            'commission_balance' => $user->commission_balance,
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out']);
    }
}
