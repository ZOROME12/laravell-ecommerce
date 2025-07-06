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
    public function store(Product $product)
    {
        $user = auth()->user();

        // Check if product already in the cart
        $cartItem = $user->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Increment quantity
            $cartItem->quantity++;
            $cartItem->save();
        } else {
            // Create new cart item
            $user->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => 1,
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
}
