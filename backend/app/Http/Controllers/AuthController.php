<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        $referrerId = null;
        if (!empty($data['referral_code'])) {
            $referrerId = User::query()->where('referral_code', $data['referral_code'])->value('id');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
            'referral_code' => Str::upper(Str::random(8)),
            'referred_by' => $referrerId,
            'wallet_balance' => 0,
            'is_active' => true,
        ]);

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages(['email' => ['Account is suspended']]);
        }

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
