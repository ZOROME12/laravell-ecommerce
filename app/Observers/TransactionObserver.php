<?php

namespace App\Observers;

use App\Models\Transaction; // Assuming your model is named Transaction
use App\Models\SalesReportLog;
use App\Models\Category; // To get category name
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     * Checks if a newly created transaction is already completed and logs it.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction): void
    {
        if ($this->isTransactionComplete($transaction)) {
            Log::info("TransactionObserver: Detected new completed transaction ID {$transaction->id}. Logging to sales report.");
            $this->logTransactionToSalesReport($transaction);
        }
    }

    /**
     * Handle the Transaction "updated" event.
     * Checks if an updated transaction has just become completed and logs it.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction): void
    {
        // Check if the relevant status fields were changed *in this update*
        $statusChanged = $transaction->isDirty('order_status') || $transaction->isDirty('payment_status');

        // Check if it's now complete AND the status actually changed (to prevent re-logging)
        if ($statusChanged && $this->isTransactionComplete($transaction)) {
             Log::info("TransactionObserver: Detected updated transaction ID {$transaction->id} is now complete. Logging to sales report.");
            $this->logTransactionToSalesReport($transaction);
        }
    }

    /**
     * Check if the transaction meets the criteria for being logged.
     *
     * @param Transaction $transaction
     * @return bool
     */
    protected function isTransactionComplete(Transaction $transaction): bool
    {
        // Define your completion criteria here
        return $transaction->order_status === 'Successful' && $transaction->payment_status === 'Done';
    }

    /**
     * Logs the transaction details to the sales_report_log table.
     *
     * @param Transaction $transaction The completed transaction instance.
     */
    protected function logTransactionToSalesReport(Transaction $transaction): void
    {
        try {
            // Eager load category if the relationship exists in your Transaction model
            // Example relationship in Transaction model:
            // public function category() { return $this->belongsTo(Category::class); }
             $transaction->loadMissing('category'); // Load if relationship exists

            $categoryName = $transaction->category->name ?? 'Manual Sales'; // Use related category name or default

             // Alternative: If you only store category_id and not a relationship
             /*
             $categoryName = 'Manual Sales'; // Default
             if ($transaction->category_id) {
                 $category = Category::find($transaction->category_id);
                 if ($category) {
                     $categoryName = $category->name;
                 }
             }
             */

            SalesReportLog::create([
                'order_number'      => 'MANUAL-' . $transaction->id, // Distinguish manual/POS sales
                'order_date'        => $transaction->created_at, // Use transaction creation date
                'customer_name'     => 'In-Store/Manual', // Placeholder, adjust if you store customer info
                'product_name'      => $transaction->item_name,
                'product_category'  => $categoryName,
                'quantity'          => $transaction->quantity,
                'price_per_item'    => $transaction->unit_price,
                'line_total'        => $transaction->quantity * $transaction->unit_price,
                'source'            => 'Offline', // Mark as Offline source
            ]);
             Log::info("TransactionObserver: Successfully logged transaction ID {$transaction->id} to sales report.");

        } catch (\Exception $e) {
            Log::error("TransactionObserver: Failed to create sales report log for transaction ID {$transaction->id}: " . $e->getMessage());
        }
    }

    // --- Registration Instruction ---
    // Open app/Providers/EventServiceProvider.php
    // Add these lines at the top:
    // use App\Models\Transaction; // Or your actual Transaction model namespace
    // use App\Observers\TransactionObserver;
    //
    // Then, inside the $observers array, add:
    // Transaction::class => [TransactionObserver::class],
    // --- End Registration Instruction ---
}
