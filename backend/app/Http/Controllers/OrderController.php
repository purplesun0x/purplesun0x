<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(private readonly ReferralService $referralService)
    {
    }

    public function index(Request $request)
    {
        return response()->json(
            $request->user()->orders()->with('items.product')->latest()->paginate(10)
        );
    }

    public function show(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);

        return response()->json($order->load('items.product'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|array',
            'payment_method' => 'required|string|in:card,paystack,stripe,cod',
            'payment_status' => 'nullable|string|in:pending,paid,failed',
        ]);

        $order = DB::transaction(function () use ($data, $request) {
            $total = 0;
            $preparedItems = [];

            foreach ($data['items'] as $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);
                abort_if($product->stock < $item['quantity'], 422, "Insufficient stock for {$product->name}");

                $linePrice = $product->price * $item['quantity'];
                $total += $linePrice;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $total,
                'status' => 'pending',
                'payment_status' => $data['payment_status'] ?? 'pending',
                'shipping_address' => $data['shipping_address'],
                'payment_method' => $data['payment_method'],
            ]);

            foreach ($preparedItems as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        if ($order->payment_status === 'paid') {
            $this->referralService->processCommissionForOrder($order);
        }

        return response()->json($order->load('items.product'), 201);
    }
}
