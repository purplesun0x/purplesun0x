<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(): JsonResponse
    {
        $salesByMonth = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(total_price) as revenue')
        )
            ->where('payment_status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'sales_by_month' => $salesByMonth,
            'new_users' => User::select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as count'))
                ->groupBy('day')
                ->latest('day')
                ->take(30)
                ->get(),
        ]);
    }
}
