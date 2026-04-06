<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('referrer');
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        return response()->json($query->paginate(20));
    }

    public function status(Request $request, User $user)
    {
        $data = $request->validate(['is_active' => 'required|boolean']);
        $user->update($data);

        return response()->json($user);
    }
}
