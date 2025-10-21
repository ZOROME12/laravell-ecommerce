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
            // NOTE: 'subtotal', 'shipping_cost', 'payment_method', 'payment_reference_no', 
            // AND 'payment_screenshot_path' are now assumed to exist based on previous errors.
            
            // Add ONLY the last remaining missing field required by the Order model's $fillable array.
            
            // $table->string('payment_screenshot_path')->nullable()->after('payment_reference_no'); // Removed, assumed exists
            
            // Add system origin field
            if (!Schema::hasColumn('orders', 'origin')) {
                 $table->string('origin')->default('cart')->after('payment_screenshot_path'); 
            }

            // IMPORTANT: If your 'order_id' is missing, uncomment the line below:
            // if (!Schema::hasColumn('orders', 'order_id')) {
            //      $table->string('order_id')->unique()->after('id');
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                // 'payment_screenshot_path', // Assumed column exists
                'origin'
            ]);
        });
    }
};
