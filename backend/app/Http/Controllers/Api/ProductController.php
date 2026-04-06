<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->where('is_active', true);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->input('rating'));
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price');
        } elseif ($sort === 'popularity') {
            $query->orderByDesc('rating');
        } else {
            $query->latest();
        }

        return response()->json($query->paginate(12));
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Product::findOrFail($id));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|integer',
            'image' => 'nullable|url',
        ]);

        $product = Product::create($validated + ['is_active' => true, 'rating' => 0]);
        return response()->json($product, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->update($request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|integer',
            'image' => 'nullable|url',
            'is_active' => 'sometimes|boolean',
        ]));

        return response()->json($product);
    }

    public function destroy(int $id): JsonResponse
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
