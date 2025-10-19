<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\SquareServices;

class ProductController extends Controller
{
    protected $squareService;

    public function __construct(SquareServices $squareService)
    {
        $this->squareService = $squareService;
    }

    public function index()
    {
        $products = Product::with('category')->get()->map(function ($product) {
            $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
            return $product;
        });

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'description' => 'required',
            'image'       => 'required|image',
            'category_id' => 'required|exists:categories,id',
        ]);

        $path = $request->file('image')->store('products', 'public');

        // 1. Create the product in your local database
        $product = Product::create([
            'name'        => $validated['name'],
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'description' => $validated['description'],
            'image'       => $path,
            'category_id' => $validated['category_id'],
        ]);

        // 2. Create the product in Square's catalog
        $squareIds = $this->squareService->createOrUpdateProduct($product);

        if ($squareIds) {
            // 3. Save the Square IDs to your product
            $product->square_item_id = $squareIds['item_id'];
            $product->square_variation_id = $squareIds['variation_id'];
            $product->save();

            // 4. **THE FIX**: Set the initial inventory in Square
            $this->squareService->setInventory($squareIds['variation_id'], $product->stock);
        }

        $product->load('category');
        $product->image_url = asset('storage/' . $product->image);

        return response()->json([
            'message' => 'Uploaded',
            'product' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'description' => 'required|string',
            'image'       => 'nullable|image',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update($validated);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
            $product->save();
        }
        
        // Update the product in Square's catalog
        $squareIds = $this->squareService->createOrUpdateProduct($product, $product->square_item_id);

        if ($squareIds) {
            $product->square_variation_id = $squareIds['variation_id'];
            $product->save();

            // **THE FIX**: Update the inventory in Square
            $this->squareService->setInventory($squareIds['variation_id'], $product->stock);
        }

        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return response()->json([
            'message' => 'Updated',
            'product' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
