<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'users' => User::query()->count(),
            'orders' => Order::query()->count(),
            'products' => Product::query()->count(),
            'revenue' => Order::query()->where('payment_status', 'paid')->sum('total_price'),
        ]);
    }

    public function analytics()
    {
        return response()->json([
            'sales_by_status' => Order::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->get(),
            'users_last_30_days' => User::query()
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
        ]);
    }
}
