<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- ADDED THIS LINE
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Services\SquareServices;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    protected $squareService;

    public function __construct(SquareServices $squareService)
    {
        $this->squareService = $squareService;
    }

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
                'tracking_stage' => 'Pending',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->order_id,
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
                'tracking_stage' => 'Pending',
            ]);

            OrderItem::create([
                'order_id' => $order->order_id,
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

    // =========================================================================
    // == THIS IS THE UPDATED METHOD THAT FIXES THE STOCK ISSUE ==
    // =========================================================================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $order = Order::with('items.product')->findOrFail($id);
        $newStatus = $request->input('status');

        // Only run stock logic if the order is being approved for the first time
        if ($newStatus === 'Approved' && $order->status !== 'Approved') {
            try {
                // Use a transaction for safety. It's all or nothing.
                DB::transaction(function () use ($order) {
                    foreach ($order->items as $item) {
                        $product = $item->product;

                        // Check if product exists and there's enough stock
                        if ($product && $product->stock >= $item->quantity) {
                            // DECREMENT LOCAL MYSQL STOCK
                            $product->stock -= $item->quantity;
                            $product->save();
                        } else {
                            // If stock is insufficient, cancel the entire operation
                            throw new \Exception('Not enough stock for product: ' . $product->name);
                        }
                        
                        // ADJUST SQUARE INVENTORY (Your existing logic)
                        if ($product && $product->square_variation_id) {
                            $this->squareService->adjustInventory(
                                $product->square_variation_id,
                                $item->quantity
                            );
                        }
                    }
                });
            } catch (\Exception $e) {
                // Return an error if stock deduction fails
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        // Update the order status only after stock logic is successful
        $order->status = $newStatus;
        $order->save();

        // Notification Logic (Your existing logic)
        if (in_array(strtolower($newStatus), ['approved', 'rejected'])) {
            $itemsText = $order->items->map(function ($item) {
                $sizeText = $item->size ? " (Size: {$item->size})" : "";
                return "{$item->quantity}× {$item->product->name}{$sizeText}";
            })->join(', ');
            
            $statusText = ucfirst(strtolower($newStatus));
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

    public function updateTrackingStage(Request $request, $id)
    {
        $request->validate([
            'tracking_stage' => 'required|string'
        ]);

        $order = Order::with('items.product')->findOrFail($id);
        $order->tracking_stage = $request->tracking_stage;
        $order->save();

        if (strtolower($request->tracking_stage) === 'delivered') {
            $lineItems = [];
            foreach ($order->items as $item) {
                if ($item->product && $item->product->square_variation_id) {
                    $lineItems[] = [
                        'quantity'          => (string) $item->quantity,
                        'catalog_object_id' => $item->product->square_variation_id,
                    ];
                }
            }

            if (!empty($lineItems)) {
                $orderPayload = [
                    'order' => [
                        'location_id'  => env('SQUARE_LOCATION_ID'),
                        'line_items'   => $lineItems,
                        'reference_id' => $order->order_id,
                        'note'         => 'Paid via ' . $order->payment_method . ' on website.'
                    ],
                    'idempotency_key' => (string) \Illuminate\Support\Str::uuid()
                ];
                
                $this->squareService->createOrder($orderPayload);
            }
        }

        return response()->json([
            'message' => 'Tracking stage updated successfully',
            'order'   => $order
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

    public function downloadReceipt($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        $pdf = Pdf::loadView('pdf.receipt', compact('order'))
                   ->setPaper('A5', 'portrait');

        return $pdf->download('Receipt_' . $order->order_id . '.pdf');
    }
}