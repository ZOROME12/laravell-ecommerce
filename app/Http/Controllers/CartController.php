<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Show the user's cart
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        return view('cart.my-cart', compact('cartItems'));
    }

    // Add product to cart or update quantity
    public function store(Request $request, Product $product)
    {
        // Validate size and quantity
        $request->validate([
            'size' => 'required|string|in:S,M,L,XL',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $user = auth()->user();
        $size = $request->input('size');
        $quantity = $request->input('quantity', 1);

        // Ensure the CartItem model is fillable for size
        // Make sure CartItem.php has:
        // protected $fillable = ['user_id', 'product_id', 'quantity', 'size'];

        // Check if the product with the same size already exists in cart
        $cartItem = $user->cartItems()
            ->where('product_id', $product->id)
            ->where('size', $size)
            ->first();

        if ($cartItem) {
            // Increment quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Create new cart item with size
            $user->cartItems()->create([
                'product_id' => $product->id,
                'size' => $size,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    // Remove item from cart
    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Item removed from cart!');
    }

    // Update quantity in cart
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'action' => 'nullable|string|in:increase,decrease'
        ]);

        if ($request->input('action') === 'increase') {
            $cartItem->quantity++;
        } elseif ($request->input('action') === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity--;
        } elseif ($request->has('quantity')) {
            $cartItem->quantity = $request->input('quantity');
        }

        $cartItem->save();

        return back()->with('success', 'Cart updated successfully!');
    }
}
