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
        Schema::table('orders', function (Blueprint $table) {
            // Add the missing 'shipping_cost' column as a decimal
            // We can place it after 'subtotal' for logical ordering.
            $table->decimal('shipping_cost', 8, 2)->default(0.00)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the column if the migration is rolled back
            $table->dropColumn('shipping_cost');
        });
    }
};