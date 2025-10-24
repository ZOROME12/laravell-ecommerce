<?php

namespace App\Http\Controllers;

use App\Models\SalesReportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade for raw queries if needed
use Illuminate\Support\Carbon; // Import Carbon for date manipulation

class ReportController extends Controller
{
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

        // IMPORTANT: Pending orders still need to be fetched live
        // You'll need to fetch this count separately in your JS or add another API endpoint for it.
        // For simplicity, we are only returning data from sales_report_log here.

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
            // In a real export, you'd likely format this data or use a package like Laravel Excel.
            // For now, just return the raw data.
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
                // Fetches all individual log entries within the date range
                $data = SalesReportLog::whereBetween('order_date', [$startDate, $endDate])
                    ->select(
                        'order_date as Date',
                        'order_number as OrderNumber',
                        'customer_name as Customer', // Added customer name
                        'product_name as Item',
                        'product_category as Category',
                        'price_per_item as UnitPrice', // <<< CORRECTED COLUMN NAME
                        'quantity as Quantity',
                        'line_total as TotalAmount',
                        'source as Source'
                    )
                    ->orderBy('order_date', 'asc')
                    ->get();
                break;

            case 'summary':
                // Groups all sales by date and calculates totals
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
                // Groups all sales by category and calculates totals
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

        // 4. Return the data
        // [MODIFICATION] Return empty array with 200 OK instead of 404
        // This prevents the fetch() in JS from throwing an error, allowing
        // the JS to show the "No data found" message gracefully.
        // if (count($data) === 0) {
        //      return response()->json([], 200); // Send empty array, OK status
        // }

        // Return data even if empty, let JS handle the "no data" message
         return response()->json($data);
    }
}
