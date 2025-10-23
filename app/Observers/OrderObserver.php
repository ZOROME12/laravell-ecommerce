<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\SalesReportLog;
use Illuminate\Support\Facades\Log; // For logging errors

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     *
     * Listens for changes in the Order model, specifically when the tracking_stage
     * transitions to 'Delivered', indicating a completed online sale.
     *
     * @param  \App\Models\Order  $order The order instance that was updated.
     * @return void
     */
    public function updated(Order $order): void
    {
        // --- DEBUG LOGGING START ---
        Log::info("OrderObserver: 'updated' event triggered for Order ID {$order->id}. Checking tracking_stage...");

        // Check original vs current value for tracking_stage
        if ($order->isDirty('tracking_stage')) {
            $originalStage = $order->getOriginal('tracking_stage');
            $newStage = $order->tracking_stage;
            Log::info("OrderObserver: tracking_stage changed from '{$originalStage}' to '{$newStage}' for Order ID {$order->id}.");
        } else {
             Log::info("OrderObserver: tracking_stage did NOT change for Order ID {$order->id}.");
        }
         // --- DEBUG LOGGING END ---


        // Check if the 'tracking_stage' field was actually changed in this update
        // and if its new value is 'Delivered'.
        if ($order->isDirty('tracking_stage') && $order->tracking_stage === 'Delivered') {
            Log::info("OrderObserver: Condition met! Order {$order->order_id} marked as Delivered. Attempting to log to sales report...");
            $this->logOrderToSalesReport($order);
        } else {
             Log::info("OrderObserver: Condition NOT met for logging Order ID {$order->id}.");
        }


        // --- IMPORTANT ---
        // You might have other conditions that signify a completed order.
        // For example, if you ONLY update the main 'status' column to 'Completed'
        // instead of 'tracking_stage', you would check for that here:
        /*
        if ($order->isDirty('status') && $order->status === 'Completed') { // Or 'Approved', depending on your flow
             Log::info("OrderObserver: Detected order {$order->order_id} status changed to Completed. Logging to sales report.");
             $this->logOrderToSalesReport($order);
        }
        */
        // Choose ONLY ONE condition ('tracking_stage' or 'status') that truly marks
        // the order as finished and ready to be logged permanently. Using both might log duplicates.
    }

    /**
     * Logs the line items of a completed order to the sales_report_log table.
     *
     * Iterates through each item in the order and creates a corresponding entry
     * in the sales report log, capturing details at the time of completion.
     *
     * @param Order $order The completed order instance.
     */
    protected function logOrderToSalesReport(Order $order): void
    {
         Log::info("OrderObserver: Entered logOrderToSalesReport for Order ID {$order->id} ({$order->order_id}). Attempting to load relations...");
        // Eager load related data (items, product, category) in one go
        // This prevents multiple database queries inside the loop (N+1 problem).
        // Ensure your Order model has the correct relationships defined:
        // - `items()` relationship returning hasMany(OrderItem::class)
        // - OrderItem model has `product()` relationship returning belongsTo(Product::class)
        // - Product model has `category()` relationship returning belongsTo(Category::class)
        try {
             $order->loadMissing(['items.product.category', 'user']); // Also load user if needed for customer name
             Log::info("OrderObserver: Relations loaded successfully for Order ID {$order->id}.");
        } catch (\Exception $e) {
            Log::error("OrderObserver: FAILED to load relations for Order ID {$order->id}: " . $e->getMessage());
            return; // Stop if relations fail
        }


        if (!$order->items || $order->items->isEmpty()) { // Check if items collection is empty
             Log::warning("OrderObserver: Order {$order->order_id} has no items loaded or items collection is empty. Cannot log sales report.");
             return;
        }
         Log::info("OrderObserver: Found {$order->items->count()} items for Order ID {$order->id}. Starting loop...");

        foreach ($order->items as $item) {
             Log::debug("OrderObserver: Processing Item ID {$item->id} for Order ID {$order->id}...");
            try {
                // Basic check if product data is available
                if (!$item->product) {
                    Log::warning("OrderObserver: Skipping sales log for item ID {$item->id} in order {$order->order_id} - missing product data.");
                    continue;
                }
                 Log::debug("OrderObserver: Item ID {$item->id} has product: {$item->product->name}");

                // Create a record in the sales_report_log table
                $logEntry = SalesReportLog::create([
                    'order_number'      => $order->order_id, // Use the unique order identifier
                    'order_date'        => $order->created_at, // Use the original order creation date
                    'customer_name'     => $order->delivery_name ?? $order->user->name ?? 'Online Customer', // Get customer name
                    'product_name'      => $item->product->name,
                    'product_category'  => $item->product->category->name ?? 'Uncategorized', // Get category name safely
                    'quantity'          => $item->quantity,
                    'price_per_item'    => $item->price, // Price at the time the order item was created
                    'line_total'        => $item->quantity * $item->price,
                    'source'            => 'Online', // Mark this as an online order
                ]);
                 Log::info("OrderObserver: Successfully created SalesReportLog entry ID {$logEntry->id} for Item ID {$item->id} (Order: {$order->order_id}).");

            } catch (\Exception $e) {
                // Log any specific errors encountered during the creation process
                Log::error("OrderObserver: Failed to create sales report log for item ID {$item->id} (Order: {$order->order_id}): " . $e->getMessage(), ['exception' => $e]); // Log full exception
                // Continue to the next item even if one fails
            }
        }
         Log::info("OrderObserver: Finished processing items for order {$order->order_id}.");
    }

     // --- Registration Instruction ---
     // Open app/Providers/EventServiceProvider.php
     // Add these lines at the top:
     // use App\Models\Order;
     // use App\Observers\OrderObserver;
     //
     // Then, inside the $observers array, add:
     // Order::class => [OrderObserver::class],
     // --- End Registration Instruction ---
}

