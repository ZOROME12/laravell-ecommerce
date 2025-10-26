// C:\Users\user\Desktop\EasePrint\laravell-ecommerce\database\migrations/2025_10_21_210357_fix_missing_order_payment_fields.php

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
        // Check if the 'orders' table exists before modifying it
        if (Schema::hasTable('orders')) {
            // Check if the 'subtotal' column is missing
            if (!Schema::hasColumn('orders', 'subtotal')) {
                Schema::table('orders', function (Blueprint $table) {
                    // Add the missing 'subtotal' column
                    // Use 'after' to place it logically, for example, after 'total'
                    // Assuming 'subtotal' stores a decimal value like 'total' and 'shipping_cost'
                    $table->decimal('subtotal', 8, 2)->default(0.00)->after('total');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is important for rolling back the migration
        if (Schema::hasTable('orders')) {
            if (Schema::hasColumn('orders', 'subtotal')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropColumn('subtotal');
                });
            }
        }
    }
};