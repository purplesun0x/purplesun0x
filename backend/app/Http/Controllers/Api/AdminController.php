<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        return response()->json(User::latest()->paginate(20));
    }

    public function orders()
    {
        return response()->json(Order::with(['items.product', 'user'])->latest()->paginate(20));
    }

    public function assignRoles(Request $request, Admin $admin)
    {
        $payload = $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $roleIds = Role::whereIn('name', $payload['roles'])->pluck('id');
        $admin->roles()->sync($roleIds);

        return response()->json(['message' => 'Roles updated']);
    }
}
