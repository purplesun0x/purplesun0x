<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', true)->with('category:id,name,slug');

        $query->when($request->filled('search'), fn ($q) =>
            $q->where('name', 'like', '%' . $request->search . '%'));

        $query->when($request->filled('category'), fn ($q) =>
            $q->whereHas('category', fn ($c) => $c->where('slug', $request->category)));

        $query->when($request->filled('min_price'), fn ($q) => $q->where('price', '>=', $request->min_price));
        $query->when($request->filled('max_price'), fn ($q) => $q->where('price', '<=', $request->max_price));

        return response()->json($query->paginate(12));
    }

    public function show(Product $product)
    {
        $product->load(['screenshots', 'reviews' => fn ($q) => $q->where('is_approved', true)]);

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return response()->json(compact('product', 'related'));
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'name' => 'required|string|max:180',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'demo_url' => 'nullable|url',
            'script_file' => 'required|file|mimes:zip,rar,7z',
            'thumbnail' => 'nullable|image',
        ]);

        $payload['slug'] = Str::slug($payload['name']) . '-' . Str::lower(Str::random(6));
        $payload['script_file_path'] = $request->file('script_file')->store('scripts', 'private');
        $payload['thumbnail_path'] = $request->file('thumbnail')?->store('thumbnails', 'public');

        $product = Product::create($payload);

        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $payload = $request->validate([
            'name' => 'sometimes|string|max:180',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'demo_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $product->update($payload);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
