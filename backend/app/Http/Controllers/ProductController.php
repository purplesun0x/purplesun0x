<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('category')->where('is_active', true);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $sortBy = $request->input('sort_by', 'latest');
        match ($sortBy) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'popularity' => $query->orderByDesc('rating'),
            default => $query->latest(),
        };

        return response()->json($query->paginate(12));
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|url',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        return response()->json(Product::create($data), 201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:200',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'sometimes|url',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'is_active' => 'sometimes|boolean',
        ]);

        $product->update($data);

        return response()->json($product->fresh());
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
