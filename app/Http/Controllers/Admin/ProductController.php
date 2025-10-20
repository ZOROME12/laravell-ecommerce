<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\Services\SquareServices;
use Illuminate\Support\Facades\DB;

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

    // ADDED THIS METHOD FOR EFFICIENCY
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
        return response()->json($product);
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

        $product = Product::create([
            'name'        => $validated['name'],
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'description' => $validated['description'],
            'image'       => $path,
            'category_id' => $validated['category_id'],
        ])->load('category');

        $squareIds = $this->squareService->createOrUpdateProduct($product);

        if ($squareIds) {
            $product->square_item_id = $squareIds['item_id'];
            $product->square_variation_id = $squareIds['variation_id'];
            $product->save();

            $this->squareService->setInventory($squareIds['variation_id'], $product->stock);
        }

        $product->image_url = asset('storage/' . $product->image);

        return response()->json([
            'message' => 'Uploaded',
            'product' => $product
        ]);
    }

    // THIS IS THE UPDATED METHOD
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
        $product->fill($validated);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }
        $product->save();

        if ($product->square_item_id) {
            $squareIds = $this->squareService->createOrUpdateProduct($product, $product->square_item_id);

            if ($squareIds) {
                $this->squareService->setInventory($squareIds['variation_id'], $product->stock);
            }
        }
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
        return response()->json(['message' => 'Updated', 'product' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->square_item_id) {
            $this->squareService->deleteProduct($product->square_item_id);
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function recordPosSale(Request $request)
    {
        // Validate that the frontend is sending an array of items.
        $validated = $request->validate([
            'items'         => 'required|array',
            'items.*.id'      => 'required|string', // This is the square_variation_id
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Use a database transaction. If any item fails to update,
        // the entire operation is cancelled to prevent partial stock updates.
        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $item) {
                $product = Product::where('square_variation_id', $item['id'])->first();

                // If we found the product in our database
                if ($product) {
                    // Check if there is enough stock
                    if ($product->stock >= $item['quantity']) {
                        // Decrease stock by the amount sold and save it.
                        $product->stock -= $item['quantity'];
                        $product->save();
                    } else {
                        // If not enough stock, cancel the transaction.
                        throw new \Exception('Not enough stock for product: ' . $product->name);
                    }
                }
                // If a product from Square isn't in our local DB, we just ignore it.
            }

            // If all items updated successfully, commit the changes.
            DB::commit();

        } catch (\Exception $e) {
            // If any error occurred, roll back all database changes.
            DB::rollBack();

            // Return an error message to the frontend.
            return response()->json(['error' => 'Failed to update local stock.', 'details' => $e->getMessage()], 500);
        }

        // This response is now just for confirming the local stock update.
        // The Square API response is handled by your SquareController.
        return response()->json(['message' => 'Local database stock updated successfully.']);
    }
}