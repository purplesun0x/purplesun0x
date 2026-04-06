<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $order = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($order);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:stripe,paystack,cod',
        ]);

        $order = DB::transaction(function () use ($request, $validated) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => 0,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            $total = 0;
            foreach ($validated['items'] as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);
                $lineTotal = $product->price * $item['quantity'];
                $total += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            $order->update([
                'total_price' => $total,
                'status' => 'processing',
                'payment_status' => 'paid', // replace after webhook verification
            ]);

            $this->creditReferralCommission($request->user(), $order);

            return $order->load('items.product');
        });

        return response()->json($order, 201);
    }

    private function creditReferralCommission(User $buyer, Order $order): void
    {
        if (!$buyer->referred_by) {
            return;
        }

        $referrer = User::find($buyer->referred_by);
        if (!$referrer || $referrer->id === $buyer->id) {
            return;
        }

        $percentage = config('referral.commission_percentage', 5);
        $commission = round(($order->total_price * $percentage) / 100, 2);

        $referrer->increment('wallet_balance', $commission);

        Referral::updateOrCreate(
            ['referrer_id' => $referrer->id, 'referred_user_id' => $buyer->id],
            ['commission' => DB::raw('commission + ' . $commission), 'status' => 'credited', 'order_id' => $order->id]
        );
    }
}
