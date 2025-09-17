<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Store order from whole cart
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'delivery_name' => 'required|string|max:255',
            'delivery_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:1000',
        ]);

        $cartItems = Auth::user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty!');
        }

        DB::transaction(function () use ($cartItems, $request) {
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'payment_method' => $request->payment_method,
                'delivery_name' => $request->delivery_name,
                'delivery_phone' => $request->delivery_phone,
                'delivery_address' => $request->delivery_address,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            Auth::user()->cartItems()->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    // Show orders list for logged-in user
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->get();
        return view('orders.index', compact('orders'));
    }

    // Show place order (checkout) page for whole cart
    public function place()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();

        return view('orders.place-order', compact('cartItems'));
    }

    // Show place order page for a single product (Order Now)
    public function placeSingle(Product $product)
    {
        return view('orders.place-order-single', compact('product'));
    }

    // Store order for a single product (from place order single page)
    public function storeSingle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'delivery_name' => 'required|string|max:255',
            'delivery_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:1000',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        $total = $product->price * $quantity;

        DB::transaction(function () use ($product, $quantity, $total, $request) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'payment_method' => $request->payment_method,
                'delivery_name' => $request->delivery_name,
                'delivery_phone' => $request->delivery_phone,
                'delivery_address' => $request->delivery_address,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }


    // Return all orders as JSON for Electron app
    public function apiIndex()
    {
        $orders = Order::with(['items.product', 'user'])->latest()->get();
        return response()->json($orders);
    }
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        return response()->json(['message' => 'Status updated successfully', 'order' => $order]);
    }

    public function destroy($id)
    {
        $order = \App\Models\Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
