<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Notification; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderController extends Controller
{
    // Store order from whole cart
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'delivery_name' => 'required|string|max:255',
            'delivery_phone' => 'required|string|max:20',
        ]);

        $cartItems = Auth::user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty!');
        }

        try {
            DB::beginTransaction();

            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'payment_method' => $request->payment_method,
                'delivery_name' => $request->delivery_name,
                'delivery_phone' => $request->delivery_phone,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->order_id, // store string order_id
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity ?? 1,
                    'price' => $item->product->price,
                    'size' => $item->size ?? null,
                ]);
            }

            Auth::user()->cartItems()->delete();

            DB::commit();

            return redirect()->route('order.successCart', $order->id);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Order placement failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('cart.index')->with('error', 'Something went wrong while placing your order.');
        }
    }

    // Show orders list for logged-in user
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->get();
        return view('orders.index', compact('orders'));
    }

    public function place()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        return view('orders.place-order', compact('cartItems'));
    }

    public function placeSingle(Product $product, Request $request)
    {
        $size = $request->query('size');
        return view('orders.place-order-single', compact('product', 'size'));
    }

    public function storeSingle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'delivery_name' => 'required|string|max:255',
            'delivery_phone' => 'required|string|max:20',
            'size' => 'nullable|string|max:10',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $total = $product->price * $quantity;

        $order = DB::transaction(function () use ($product, $quantity, $total, $request) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'payment_method' => $request->payment_method,
                'delivery_name' => $request->delivery_name,
                'delivery_phone' => $request->delivery_phone,
            ]);

            OrderItem::create([
                'order_id' => $order->order_id, // store string order_id
                'product_id' => $product->id,
                'quantity' => $quantity ?? 1,
                'price' => $product->price,
                'size' => $request->size ?? null,
            ]);

            return $order;
        });

        return redirect()->route('order.successSingle', $order->id);
    }

    public function successSingle(Order $order)
    {
        $order->load('items.product');
        return view('orders.success', compact('order'));
    }

    public function successCart($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('orders.success-cart', compact('order'));
    }

    public function apiIndex()
    {
        $orders = Order::with(['items.product', 'user'])->latest()->get();
        return response()->json($orders);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        $order->status = $request->status;
        $order->save();

        $itemsText = $order->items->map(function ($item) {
            $sizeText = $item->size ? " (Size: {$item->size})" : "";
            return "{$item->quantity}× {$item->product->name}{$sizeText}";
        })->join(', ');

        if (strtolower($request->status) === 'approved' || strtolower($request->status) === 'rejected') {
            $statusText = ucfirst(strtolower($request->status));
            $message = "Order No. #{$order->order_id} containing: {$itemsText} has been {$statusText}.";

            Notification::create([
                'user_id' => $order->user_id,
                'message' => $message,
                'is_read' => false,
            ]);
        }

        return response()->json([
            'message' => 'Status updated successfully',
            'order' => $order
        ]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
