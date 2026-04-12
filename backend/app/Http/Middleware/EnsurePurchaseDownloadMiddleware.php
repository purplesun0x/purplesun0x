<?php

namespace App\Http\Middleware;

use App\Models\OrderItem;
use Closure;
use Illuminate\Http\Request;

class EnsurePurchaseDownloadMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $orderItem = OrderItem::findOrFail($request->route('orderItem'));

        if ($orderItem->order->user_id !== $request->user()->id || $orderItem->order->status !== 'paid') {
            return response()->json(['message' => 'Unauthorized download access.'], 403);
        }

        return $next($request);
    }
}
