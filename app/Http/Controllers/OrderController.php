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
use App\Services\SquareServices;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\Admin; // Ensure this is present for instanceof check

class OrderController extends Controller
{
    protected $squareService; // Define property

    public function __construct(SquareServices $squareService)
    {
        $this->squareService = $squareService;
    }

    // *** MODIFIED store METHOD BELOW ***
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string', // Expect 'cod' or 'gcash_manual'
            'delivery_name' => 'required|string|max:255',
            // *** UPDATED VALIDATION RULE for delivery_phone ***
            'delivery_phone' => [
                'required',
                'string',
                'regex:/^9[0-9]{9}$/' // Expect 10 digits starting with 9
            ],
        ]);

        $cartItems = Auth::user()->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty!');
        }

        // Calculate totals before transaction
        $subtotal = $cartItems->sum(function ($item) {
             // Calculate based on available stock
             $stock = optional($item->product)->stock ?? 0;
             if ($stock <= 0) return 0;
             $quantity = min($item->quantity ?? 1, $stock);
             $price = optional($item->product)->price ?? 0;
             return $price * $quantity;
        });
        $shippingCost = 0.00; // Hardcoded shipping for cart
        $total = $subtotal + $shippingCost;

        $order = null; // Initialize order variable

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_id' => 'EASE-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)), // Generate Order ID
                'total' => $total,
                'subtotal' => $subtotal, // Store subtotal
                'shipping_cost' => $shippingCost, // Store shipping cost
                'payment_method' => $request->payment_method, // Will be 'gcash_manual' from hidden input
                'status' => 'pending_payment', // Start as pending payment
                'delivery_name' => $request->delivery_name,
                // *** ADD '+63' PREFIX HERE BEFORE SAVING ***
                'delivery_phone' => '+63' . $request->delivery_phone,
                'tracking_stage' => null, // Set tracking stage to null initially
                'origin' => 'cart', // Set origin flag for cart
            ]);

            foreach ($cartItems as $item) {
                // Check stock before creating order item (redundant check, but safe)
                $stock = optional($item->product)->stock ?? 0;
                $requestedQuantity = $item->quantity ?? 1;
                $finalQuantity = 0; // Default to 0

                if ($stock > 0) { // Only add item if it was in stock
                    $finalQuantity = min($requestedQuantity, $stock); // Ensure quantity doesn't exceed stock
                    if ($finalQuantity < 1) $finalQuantity = 1; // Should not happen if stock > 0 but safety
                }

                // Only create OrderItem if there's stock and quantity
                if ($finalQuantity > 0 && $item->product) {
                    OrderItem::create([
                        'order_id' => $order->id, // Use the auto-increment ID
                        'product_id' => $item->product_id,
                        'quantity' => $finalQuantity, // Use the adjusted quantity
                        'price' => $item->product->price ?? 0,
                        'size' => $item->size ?? null,
                    ]);
                    // Optional: Decrement stock here if needed
                    // $item->product->decrement('stock', $finalQuantity);
                } else if ($item->product) {
                     Log::warning('Skipped adding out-of-stock item to order.', ['order_id' => $order->id, 'product_id' => $item->product_id]);
                } else {
                     Log::warning('Skipped adding item with missing product to order.', ['order_id' => $order->id, 'cart_item_id' => $item->id]);
                }

            }

            // Clear cart only after successful item creation attempts
            Auth::user()->cartItems()->delete();

            // Redirect logic for cart
            // Since COD is removed from the form, payment_method should always be 'gcash_manual' here
             if (strtolower($request->payment_method) === 'gcash_manual') {
                 // For GCash manual orders, commit and go to cart payment page
                 DB::commit();
                 return redirect()->route('payment.showCart', ['order' => $order->id])
                         ->with('status', 'Order placed! Please complete payment.');
             } else {
                 // Fallback/Error case - Should ideally not happen if form only allows gcash_manual
                 Log::error('Unexpected payment method received from cart checkout.', ['payment_method' => $request->payment_method, 'order_id' => $order->id]);
                 DB::commit(); // Commit anyway? Or rollback? Decided to commit.
                 // Redirecting to successCart might be confusing, maybe back to cart with error?
                 // For now, let's redirect to payment page as if it was GCash
                 return redirect()->route('payment.showCart', ['order' => $order->id])
                         ->with('error', 'An issue occurred with the payment method selection. Please proceed with payment.');
             }


        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Cart order placement failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                // 'trace' => $e->getTraceAsString(), // Optional for debugging
            ]);

            return redirect()->route('cart.index')->with('error', 'Something went wrong while placing your order. Please check stock or try again.');
        }
    }
    // *** END OF MODIFIED store METHOD ***

    // Show orders list for logged-in user (No changes made)
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->latest()->get(); // Added latest()
        return view('orders.index', compact('orders'));
    }

    // Show page to place order from cart (No changes made)
    public function place()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        // Calculate totals for display if needed
        $subtotal = $cartItems->sum(fn($item) => ($item->quantity ?? 1) * (optional($item->product)->price ?? 0));
        $shippingCost = 0.00;
        $total = $subtotal + $shippingCost;
        return view('orders.place-order', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
    }

    // Show page to place single order (No changes made)
    public function placeSingle(Product $product, Request $request)
    {
        $size = $request->query('size');
        // Calculate totals for display
        $quantity = 1; // Default quantity for display, JS handles actual
        $subtotal = $product->price * $quantity;
        $shippingCost = 0.00;
        $total = $subtotal + $shippingCost;
        return view('orders.place-order-single', compact('product', 'size', 'subtotal', 'shippingCost', 'total'));
    }

    // storeSingle METHOD (No changes made here from previous version)
    public function storeSingle(Request $request)
    {
        // Validation - Added phone number pattern
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            // 'payment_method' => 'required|string', // Removed validation - set manually below
            'delivery_name' => 'required|string|max:255',
            'delivery_phone' => 'required|string|regex:/^9[0-9]{9}$/', // Validate 10 digits starting with 9
            'size' => 'nullable|string|max:10',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int)$request->quantity; // Ensure quantity is integer
        $size = $request->size; // Get size from request

        // Check stock
        if ($product->stock < $quantity) {
            return back()->with('error', 'Not enough stock available for ' . $product->name);
        }

        // Calculate totals
        $subtotal = $product->price * $quantity;
        $shippingCost = 0.00; // Hardcoded shipping - adjust if dynamic
        $total = $subtotal + $shippingCost;

        $order = null; // Initialize order variable outside the transaction scope

        try {
            // Use DB::transaction for safety
             $order = DB::transaction(function () use ($product, $quantity, $total, $subtotal, $shippingCost, $request, $size) {
                 // Create Order record
                 $newOrder = Order::create([
                     'user_id' => Auth::id(),
                     'order_id' => 'EASE-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)), // Generate unique Order ID
                     'total' => $total,
                     'subtotal' => $subtotal,
                     'shipping_cost' => $shippingCost,
                     'payment_method' => 'gcash_manual', // *** Set payment method explicitly ***
                     'status' => 'pending_payment', // *** SET INITIAL STATUS HERE ***
                     'delivery_name' => $request->delivery_name,
                     'delivery_phone' => '+63' . $request->delivery_phone, // Add +63 prefix
                     'tracking_stage' => null, // *** Set tracking stage to null initially ***
                     'origin' => 'single', // *** Set origin flag for single ***
                 ]);

                 // Create OrderItem record
                 OrderItem::create([
                     'order_id' => $newOrder->id, // *** Use internal ID ***
                     'product_id' => $product->id,
                     'quantity' => $quantity,
                     'price' => $product->price,
                     'size' => $size,
                 ]);

                 // Optional: Decrement stock
                 // $product->decrement('stock', $quantity);

                 return $newOrder; // Return the created order object
             });

             // *** REDIRECT TO SINGLE PAYMENT PAGE ***
             if ($order) {
                 // *** UPDATED ROUTE NAME HERE ***
                 return redirect()->route('payment.showSingle', ['order' => $order->id])
                         ->with('status', 'Order placed! Please complete payment.');
             } else {
                 throw new \Exception('Order creation failed within transaction.');
             }

        } catch (Throwable $e) {
             Log::error('Single order placement failed', [
                 'user_id' => Auth::id(),
                 'product_id' => $request->product_id,
                 'error' => $e->getMessage(),
             ]);
             return back()->with('error', 'Something went wrong while placing your order. Please try again.');
        }
    }
    // *** END OF storeSingle METHOD ***

    // Renamed this method in previous step discussions - ensure routes match this name
    // This is now used ONLY by confirmSinglePayment redirect
    public function successSingle(Order $order) // Use this name if route is 'order.successSingle'
    {
        $order->load('items.product');
        // This view should say "Payment Submitted for Verification"
        return view('orders.success', compact('order')); // Ensure this view exists and is correct
    }

    // This is now used by confirmCartPayment redirect
    public function successCart($orderId)
    {
        // Fetch the order and eager load the items and product details
        $order = Order::with('items.product')->findOrFail($orderId);

        // === TEMPORARY DEBUG CHECK ===
        // Stop execution and check the items collection
        if ($order->items->isEmpty()) {
            // Check if the items collection is empty
            // Consider logging instead of dd() in production
            Log::error('Order items collection is empty!', ['order_id' => $orderId]);
            // Optionally, redirect with an error or show a specific view
            // For now, let's proceed but log the issue.
            // dd('Items collection is empty! Check database for Order ID: ' . $orderId);
        }

        // Check if the first item has its product loaded
        // Added checks for existence before accessing properties
        if ($order->items->isNotEmpty() && $order->items->first() && $order->items->first()->product === null) {
            Log::error('Order item product relationship is NULL!', ['order_id' => $orderId, 'first_item_id' => $order->items->first()->id]);
            // Optionally, redirect or show an error
            // dd('Item product relationship is NULL! Check OrderItem model definition.');
        }

        // If the code reaches here, the data looks right.
        // ============================

        return view('orders.success-cart', compact('order'));
    }


    // --- Admin-specific Methods ---

    // *** UPDATED apiIndex METHOD ***
    public function apiIndex(Request $request)
    {
        // Get the currently authenticated user (could be User or Admin)
        $authUser = $request->user(); // Or Auth::user() depending on guard setup

        // Check if the authenticated user is an instance of the Admin model
        if ($authUser instanceof Admin) {
            // Admin: Fetch all orders with user and item details
            $orders = Order::with(['items.product', 'user'])->latest()->get();
        }
        // Otherwise, assume it's a regular User
        else if ($authUser) { // Check if authUser is not null (regular user)
            // Regular User: Fetch only their own orders
            $orders = Order::where('user_id', $authUser->id)
                ->with(['items.product']) // No need to load 'user' relation for own orders
                ->latest()
                ->get();
        } else {
             // Handle case where no user is authenticated (though middleware should prevent this)
             return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Return the fetched orders as JSON
        return response()->json($orders);
    }
    // *** END OF UPDATED apiIndex METHOD ***

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $order = Order::with('items.product')->findOrFail($id);
        $originalStatus = $order->status;
        $newStatus = $request->input('status');

        if ($originalStatus === $newStatus) {
            return response()->json(['message' => 'Order is already in the requested status.'], 400);
        }
        // Allow Approval/Rejection only if status is 'for_verification'
        if (($newStatus === 'Approved' || $newStatus === 'Rejected') && $originalStatus !== 'for_verification') {
            return response()->json(['message' => 'Order payment must be verified before approving or rejecting.'], 400);
        }

        if ($newStatus === 'Approved' && $originalStatus === 'for_verification') {
            try {
                DB::transaction(function () use ($order) {
                    foreach ($order->items as $item) {
                        $product = $item->product;
                        if ($product && $product->stock >= $item->quantity) {
                            $product->decrement('stock', $item->quantity);
                        } else {
                            throw new \Exception('Not enough stock for product: ' . ($product ? $product->name : 'ID ' . $item->product_id));
                        }
                        if ($product && $product->square_variation_id) {
                            Log::info('Square inventory adjustment needed for variation ID: ' . $product->square_variation_id . ' by quantity: -' . $item->quantity);
                             // Consider calling your SquareService here to adjust inventory if needed
                             // $this->squareService->adjustInventory($product->square_variation_id, -$item->quantity, 'Stock decremented due to Order Approval');
                        }
                    }
                });
            } catch (\Exception $e) {
                Log::error('Stock deduction failed on order approval: ' . $e->getMessage(), ['order_id' => $order->id]);
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        // Note: No stock replenishment on 'Rejected' status currently. Add if needed.

        $order->status = $newStatus;
        if ($newStatus === 'Approved') {
            $order->tracking_stage = 'Approved'; // Set initial tracking stage on approval
        } else if ($newStatus === 'Rejected') {
             $order->tracking_stage = 'Cancelled'; // Or use 'Rejected' if that's a stage in your system
        }
        $order->save();

        // Send notification for Approved/Rejected status changes
        if (in_array(strtolower($newStatus), ['approved', 'rejected'])) {
             $itemsText = $order->items->map(function ($item) {
                 $sizeText = $item->size ? " (Size: {$item->size})" : "";
                 return "{$item->quantity}x " . ($item->product ? $item->product->name : 'Unknown Product') . $sizeText;
             })->join(', ');
             $statusText = ucfirst(strtolower($newStatus)); // Ensure consistent capitalization
             $message = "Your payment for Order #{$order->order_id} ({$itemsText}) has been {$statusText}.";
             if($newStatus === 'Approved') {
                 $message .= " Your order is now being prepared.";
             }
             // You might want a different message for rejection, e.g., explaining why or next steps
             Notification::create([
                 'user_id' => $order->user_id,
                 'message' => $message,
                 'is_read' => false,
             ]);
        }

        return response()->json([
            'message' => 'Status updated successfully',
            'order' => $order->load('items.product', 'user') // Reload relations after save
        ]);
    }


    public function updateTrackingStage(Request $request, $id)
    {
       $request->validate([
           'tracking_stage' => 'required|string|in:Approved,Preparing,Shipment,Shipped Out,On Delivery,Delivered'
           // Add any other stages you might have
       ]);

       // Load the order *before* saving, to check original values if needed later
       $order = Order::with(['items.product', 'user'])->findOrFail($id);
       $originalStage = $order->tracking_stage; // Store original stage
       $newStage = $request->tracking_stage;

       // Basic validation checks
       if ($order->status !== 'Approved' && $newStage !== 'Approved') { // Allow setting to Approved initially
           return response()->json(['message' => 'Order must be approved before tracking can be updated beyond Approved.'], 400);
       }
       if (in_array($order->tracking_stage, ['Delivered', 'Cancelled'])) { // Check against current stage
           return response()->json(['message' => 'Cannot update tracking for a completed or cancelled order.'], 400);
       }
       if ($originalStage === $newStage) {
            return response()->json(['message' => 'Order is already in the requested tracking stage.'], 400); // Prevent redundant updates
       }


       // Update and Save the Order
       $order->tracking_stage = $newStage;
       $order->save(); // Save the changes to the database

       // Observer should handle logging automatically via the 'updated' event.

       // Existing logic for Square and Notifications
       if (strtolower($newStage) === 'delivered') {
           // ... (your existing Square order creation logic) ...
           Log::info('Order marked Delivered, attempting to create Square order for Order ID: ' . $order->order_id);
           // Consider moving Square logic to the Observer's 'updated' method
           // if $order->wasChanged('tracking_stage') && $order->tracking_stage === 'Delivered'
       }

       // Only send notification if the stage actually changed
       if ($originalStage !== $newStage) {
            $stageText = $newStage; // Use the new stage
            $message = "Update on Order #{$order->order_id}: Your order status is now '{$stageText}'.";
            if (strtolower($stageText) === 'delivered') {
                $message .= " Thank you for your purchase!";
            }

            Notification::create([
                'user_id' => $order->user_id,
                'message' => $message,
                'is_read' => false,
            ]);
       }

       return response()->json([
           'message' => 'Tracking stage updated successfully',
           'order'   => $order // Return the updated order (already loaded relations)
       ]);
    }


    public function destroy($id)
    {
       $order = Order::find($id);
       if (!$order) {
           return response()->json(['message' => 'Order not found'], 404);
       }
       try {
           DB::transaction(function () use ($order) {
               // Ensure related items are deleted first if foreign key constraints exist without cascade
               $order->items()->delete();
               $order->delete();
           });
           return response()->json(['message' => 'Order deleted successfully']);
       } catch (\Exception $e) {
           Log::error('Order deletion failed: ' . $e->getMessage(), ['order_id' => $id]);
           return response()->json(['message' => 'Failed to delete order.'], 500);
       }
    }

    public function downloadReceipt($orderId)
    {
       $order = Order::with('items.product')->findOrFail($orderId); // Eager load relations
       $pdf = Pdf::loadView('pdf.receipt', compact('order'))
                 ->setPaper('a5', 'portrait'); // Set paper size and orientation if needed
       return $pdf->download('Receipt_' . $order->order_id . '.pdf'); // Generate a meaningful filename
    }
}