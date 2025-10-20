<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    // Show products (web + API/Electron)
    public function index()
    {
        $products = Product::with(['category'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->get()
            ->map(function ($product) {
                $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
                return $product;
            });

        // If API request, return JSON
        if (request()->wantsJson()) {
            return response()->json($products);
        }

        return view('products.index', compact('products'));
    }

    // Show single product
    public function show(Product $product)
    {
        $product->load(['reviews.user', 'category'])->loadCount('reviews');
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        if (request()->wantsJson()) {
            return response()->json($product);
        }

        return view('products.show', compact('product'));
    }

    // Store product (API/Electron)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'image'       => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated)->load('category');
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'image'       => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load('category'),
        ]);
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
