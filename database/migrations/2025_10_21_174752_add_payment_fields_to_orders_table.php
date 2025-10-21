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
        // *** Check if 'status' column DOES NOT exist before adding ***
        // (Alternative: Just remove the 'status' line entirely if you know it exists)
        // if (!Schema::hasColumn('orders', 'status')) {
        //    $table->string('status')->default('pending_payment')->after('user_id');
        // }

        Schema::table('orders', function (Blueprint $table) {
            // *** ONLY ADD payment_reference_no ***
            // Ensure 'total' column exists before adding after it
            if (Schema::hasColumn('orders', 'total')) {
                 $table->string('payment_reference_no', 50)->nullable()->after('total');
            } else {
                // Fallback placement if 'total' doesn't exist (adjust as needed)
                $table->string('payment_reference_no', 50)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Only drop the column added by this migration's up() method
            $table->dropColumn('payment_reference_no');

            // *** Remove the line that drops 'status' if you removed it from up() ***
            // $table->dropColumn('status');
        });
    }
};