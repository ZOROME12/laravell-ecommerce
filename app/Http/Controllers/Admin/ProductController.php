<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'required',
            'image' => 'required|image'
        ]);

        $path = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
            'image' => $path
        ]);

        return response()->json(['message' => 'Uploaded', 'product' => $product]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->only(['name', 'price', 'stock', 'description']));
        return response()->json(['message' => 'Updated', 'product' => $product]);
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
