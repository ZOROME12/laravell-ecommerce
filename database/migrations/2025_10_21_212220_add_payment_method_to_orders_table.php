<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check to ensure you are not adding the column if it already exists
        if (!Schema::hasColumn('orders', 'payment_method')) {
            Schema::table('orders', function (Blueprint $table) {
                // Add the missing 'payment_method' column, placed after 'shipping_cost'
                $table->string('payment_method')->after('shipping_cost');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the column if the migration is rolled back
            $table->dropColumn('payment_method');
        });
    }
};