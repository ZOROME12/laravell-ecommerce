<?php

// This file is a guide. This is the *only* new analytics table you need.
// This one table will store your permanent sales data for all reports
// and for exporting to Excel.

// Run: php artisan make:migration create_sales_report_log_table
// File: database/migrations/xxxx_create_sales_report_log_table.php
//
// Copy everything from 'class CreateSalesReportLogTable' down.
// -----------------------------------------------------------------

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_report_log', function (Blueprint $table) {
            // This table is a permanent log.
            // When an order is 'Completed', your Laravel code should copy
            // one row into this table FOR EACH item in that order.

            $table->id(); // Simple auto-incrementing ID
            $table->string('order_number', 50)->index();
            $table->timestamp('order_date');
            $table->string('customer_name')->nullable();
            
            // Product details (copied at time of sale)
            $table->string('product_name');
            $table->string('product_category', 100)->nullable()->index();
            
            // Financials for this single line item
            $table->integer('quantity');
            $table->decimal('price_per_item', 10, 2);
            $table->decimal('line_total', 10, 2); // (quantity * price_per_item)

            // Source for the 'Earnings Breakdown' chart
            $table->enum('source', ['Online', 'Offline']);

            // We add created_at just to know when this log entry was made
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_report_log');
    }
}


// ---
// Example: How to get ALL your analytics from this ONE table
// ---
/*

// 1. Total Revenue (Dashboard Card)
SELECT SUM(line_total) as total_revenue FROM sales_report_log;

// 2. Earnings Breakdown (Analytics Chart)
SELECT source, SUM(line_total) as total
FROM sales_report_log
GROUP BY source;

// 3. Top Products (Dashboard Chart)
SELECT product_name, SUM(quantity) as total_sold
FROM sales_report_log
GROUP BY product_name
ORDER BY total_sold DESC;

// 4. Sales by Category (Analytics Chart)
SELECT product_category, SUM(quantity) as total_sold
FROM sales_report_log
GROUP BY product_category
ORDER BY total_sold DESC;

// 5. Orders Analytics (Line Chart)
SELECT DATE(order_date) as sale_date, SUM(line_total) as daily_total
FROM sales_report_log
GROUP BY DATE(order_date)
ORDER BY sale_date ASC;

*/

