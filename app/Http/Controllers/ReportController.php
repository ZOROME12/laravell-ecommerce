<?php

namespace App\Http\Controllers;

// --- Use Statements ---
use App\Models\Order; 
use App\Services\SquareServices; // Correct namespace from your SquareServices file
use App\Models\SalesReportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Carbon; 
use Illuminate\Support\Facades\Log; // Added for logging errors

class ReportController extends Controller
{
    // **** THIS IS THE MISSING PROPERTY DECLARATION ****
    protected $squareService; 
    // **** END OF MISSING PROPERTY DECLARATION ****

    // --- Constructor ---
    public function __construct(SquareServices $squareService) // Correct class name from your file
    {
        $this->squareService = $squareService;
    }

    // --- YOUR EXISTING METHODS (UNCHANGED) ---
    
    /**
     * Get summary data for the main dashboard cards (Current Month).
     * Calculates Total Revenue, Total Sales Count from the sales log.
     * Note: Pending Orders must still come from the live 'orders' table.
     */
    public function dashboardSummary(Request $request)
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // --- Current Month ---
        $currentMonthData = SalesReportLog::selectRaw('SUM(line_total) as total_revenue, COUNT(DISTINCT order_number) as total_sales_count')
            ->whereBetween('order_date', [$currentMonthStart, $currentMonthEnd])
            ->first(); // Use first() as we expect only one result row

        // --- Previous Month ---
        $previousMonthData = SalesReportLog::selectRaw('SUM(line_total) as total_revenue, COUNT(DISTINCT order_number) as total_sales_count')
            ->whereBetween('order_date', [$previousMonthStart, $previousMonthEnd])
            ->first();

        // Prepare response (ensure defaults if no data)
        $summary = [
            'current_month' => [
                'total_revenue' => $currentMonthData->total_revenue ?? 0.00,
                'total_sales_count' => $currentMonthData->total_sales_count ?? 0,
            ],
            'previous_month' => [
                'total_revenue' => $previousMonthData->total_revenue ?? 0.00,
                'total_sales_count' => $previousMonthData->total_sales_count ?? 0,
            ]
        ];

        return response()->json($summary);
    }

    /**
     * Get data for the 'Top Products Overview' donut chart and bar chart.
     * Calculates total quantity sold per product.
     */
    public function topProductsOverview(Request $request)
    {
        // Get total quantity and revenue for each product, order by revenue descending
        $topProducts = SalesReportLog::select(
                'product_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(line_total) as total_revenue')
            )
            ->groupBy('product_name')
            ->orderByDesc('total_revenue')
            ->limit(5) // Optional: limit to top 5 products
            ->get();

        // Return as JSON with 'data' key for consistency with Chart.js frontend
        return response()->json([
            'data' => $topProducts
        ]);
    }

    /**
     * Get data for the 'Orders Analytics' line chart based on selected period.
     */
    public function salesTrends(Request $request)
    {
        $period = $request->query('period', 'month'); // Default to month

        // Determine date format based on period for grouping
        $format = match ($period) {
            'day' => '%Y-%m-%d',
            'week' => '%x-W%v', // ISO 8601 week number
            'year' => '%Y',
            default => '%Y-%m', // Default to month
        };

        $trends = SalesReportLog::select(
                DB::raw("DATE_FORMAT(order_date, '$format') as period"),
                DB::raw('SUM(line_total) as total_sales') // Sum of line totals for sales value
            )
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        return response()->json($trends);
    }

    /**
      * Get data for the 'Earnings Breakdown' donut chart.
      * Calculates total earnings grouped by source ('Online' vs 'Offline').
      */
     public function earningsBreakdown(Request $request)
     {
         $breakdown = SalesReportLog::select('source', DB::raw('SUM(line_total) as total_earnings'))
             ->whereNotNull('source') // Ensure source is not null
             ->groupBy('source')
             ->get()
             ->keyBy('source'); // Key the collection by 'source' for easy access

         // Ensure both Online and Offline keys exist, defaulting to 0 if no sales for that source
         $response = [
             'Offline' => $breakdown->get('Offline') ? $breakdown->get('Offline')->total_earnings : 0.00,
             'Online' => $breakdown->get('Online') ? $breakdown->get('Online')->total_earnings : 0.00,
         ];

         return response()->json($response);
     }

      /**
       * Get data for the 'Sales by Category' bar chart.
       * Calculates total sales value grouped by product category.
       */
      public function salesByCategory(Request $request)
      {
          $sales = SalesReportLog::select('product_category', DB::raw('SUM(line_total) as total_sales_value'))
              ->whereNotNull('product_category')
              ->groupBy('product_category')
              ->orderByDesc('total_sales_value') // Order by sales value
              ->get();

          return response()->json($sales);
      }

       /**
        * Endpoint to fetch all data from sales_report_log for Excel export.
        * Consider adding pagination or filtering for very large datasets in a real application.
        */
       public function exportData(Request $request)
       {
           $data = SalesReportLog::orderBy('order_date', 'desc')->get();
           return response()->json($data);
       }

       /**
        * Handle sales report export requests from the dashboard modal.
        */
        public function exportSales(Request $request)
        {
            // 1. Validate the incoming request
            $validated = $request->validate([
                'type' => 'required|string|in:summary,detailed,category',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            // 2. Parse dates using Carbon for accurate range queries
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();

            $data = [];

            // 3. Build the query based on the requested report type using SalesReportLog
            switch ($validated['type']) {
                
                case 'detailed':
                    $data = SalesReportLog::whereBetween('order_date', [$startDate, $endDate])
                        ->select(
                            'order_date as Date',
                            'order_number as OrderNumber',
                            'customer_name as Customer', 
                            'product_name as Item',
                            'product_category as Category',
                            'price_per_item as UnitPrice', 
                            'quantity as Quantity',
                            'line_total as TotalAmount',
                            'source as Source'
                        )
                        ->orderBy('order_date', 'asc')
                        ->get();
                    break;

                case 'summary':
                    $data = SalesReportLog::whereBetween('order_date', [$startDate, $endDate])
                        ->select(
                            DB::raw('DATE(order_date) as Date'),
                            DB::raw('COUNT(DISTINCT order_number) as TotalOrders'),
                            DB::raw('SUM(quantity) as TotalItemsSold'),
                            DB::raw('SUM(line_total) as TotalRevenue')
                        )
                        ->groupBy(DB::raw('DATE(order_date)'))
                        ->orderBy('Date', 'asc')
                        ->get();
                    break;

                case 'category':
                    $data = SalesReportLog::whereBetween('order_date', [$startDate, $endDate])
                        ->select(
                            'product_category as Category',
                            DB::raw('SUM(quantity) as TotalItemsSold'),
                            DB::raw('SUM(line_total) as TotalRevenue')
                        )
                        ->whereNotNull('product_category')
                        ->groupBy('product_category')
                        ->orderBy('TotalRevenue', 'desc')
                        ->get();
                    break;
            }

            return response()->json($data);
        }

    // =================================================================
    // == FETCH ALL SALES HISTORY FUNCTION (INCLUDES THE FIX) ==
    // =================================================================

    /**
     * Fetches a combined list of sales from local 'orders' table (Online)
     * and Square API (Offline/POS).
     */
   public function fetchAllSalesHistory(Request $request)
    {
        // 1. Get Online Sales (from 'orders' table)
        $onlineSales = Order::where('status', 'Approved')
                            ->with('items.product', 'user')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // 2. Get POS Sales (from Square Service)
        $squareSalesData = $this->squareService->getSalesHistory(); 
        if (isset($squareSalesData['error'])) {
            Log::error("Failed to fetch Square sales for combined history: " . $squareSalesData['error']);
            $posSales = []; 
        } else {
            $posSales = $squareSalesData['orders'] ?? []; 
        }

        // --- NEW STEP: Get Manual Transactions (from 'transactions' table) ---
        // Fetch successful/done manual transactions. Adjust conditions if needed.
        $manualTransactions = \App\Models\Transaction::where('order_status', 'Successful') 
                                                    ->where('payment_status', 'Done')
                                                    ->orderBy('created_at', 'desc')
                                                    ->get();
        // --- END OF NEW STEP ---
        

        // 3. Format and Combine the Lists
        $combinedSales = [];

        // Format online sales
        foreach ($onlineSales as $order) {
            // Skip online orders that have a square_order_id (prevents duplicates if synced)
             if (!empty($order->square_order_id)) { 
                 continue;
             }
            $combinedSales[] = [
                'id' => $order->order_id, // Website Order ID
                'created_at' => $order->created_at->toIso8601String(),
                'total_money' => [
                    'amount' => $order->total * 100,
                    'currency' => 'PHP'
                ],
                'line_items' => $order->items->map(function ($item) {
                    return [
                        'name' => $item->product->name ?? 'Unknown Item',
                        'quantity' => (string)$item->quantity
                    ];
                })->toArray(),
                'state' => 'COMPLETED',
                'source' => 'Online' // Source identifier
            ];
        }

        // Format POS sales
        foreach ($posSales as $order) {
            if (isset($order['state']) && $order['state'] === 'CANCELED') continue; 
            if (isset($order['id'], $order['created_at'], $order['total_money'])) {
                 $combinedSales[] = [
                    'id' => $order['id'], // Square Order ID
                    'created_at' => $order['created_at'],
                    'total_money' => $order['total_money'],
                    'line_items' => array_map(function($item) {
                        $item['quantity'] = (string)($item['quantity'] ?? '0'); 
                        return $item;
                    }, $order['line_items'] ?? []), 
                    'state' => $order['state'] ?? 'COMPLETED',
                    'source' => 'POS (Square)' // Source identifier
                ];
            } else {
                 Log::warning('Square order missing essential data for combined history', ['order_id' => $order['id'] ?? 'N/A']);
            }
        }

        // --- NEW STEP: Format Manual Transactions ---
        foreach ($manualTransactions as $tx) {
             $combinedSales[] = [
                'id' => 'TXN-' . $tx->id, // Create a unique ID prefix for manual transactions
                'created_at' => $tx->created_at->toIso8601String(),
                'total_money' => [
                    'amount' => ($tx->unit_price * $tx->quantity) * 100, // Calculate total in cents
                    'currency' => 'PHP'
                ],
                // Manual transactions are single items
                'line_items' => [ 
                    [
                        'name' => $tx->item_name ?? 'Manual Item',
                        'quantity' => (string)$tx->quantity
                    ]
                ], 
                'state' => 'COMPLETED', // Assume completed if status is Successful/Done
                'source' => 'Manual Entry' // Source identifier
            ];
        }
        // --- END OF NEW STEP ---

        // 4. Sort the final combined list by date, newest first
        usort($combinedSales, function($a, $b) {
            return Carbon::parse($b['created_at'])->timestamp <=> Carbon::parse($a['created_at'])->timestamp;
        });

        // 5. Return the combined list 
        return response()->json([
            'orders' => $combinedSales
        ]);
    }

} // <-- End of ReportController class