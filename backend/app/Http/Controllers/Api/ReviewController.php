<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $payload = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'order_item_id' => 'required|exists:order_items,id',
        ]);

        $orderItem = OrderItem::findOrFail($payload['order_item_id']);
        abort_if($orderItem->product_id !== $product->id, 422, 'Invalid product for order item');
        abort_if($orderItem->order->user_id !== $request->user()->id || $orderItem->order->status !== 'paid', 403, 'Not eligible');

        $review = Review::updateOrCreate(
            ['order_item_id' => $orderItem->id],
            [
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'rating' => $payload['rating'],
                'comment' => $payload['comment'] ?? null,
                'is_approved' => true,
            ]
        );

        $avg = (float) Review::where('product_id', $product->id)->where('is_approved', true)->avg('rating');
        $count = Review::where('product_id', $product->id)->where('is_approved', true)->count();
        $product->update(['avg_rating' => $avg, 'total_ratings' => $count]);

        return response()->json($review, 201);
    }
}
