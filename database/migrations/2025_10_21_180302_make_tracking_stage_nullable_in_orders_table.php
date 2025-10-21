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
            // Change the column to allow NULLs and remove the default value
            $table->string('tracking_stage')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert back: make it NOT nullable and set the default back to 'Approved'
            // Note: Existing NULL values might cause issues if not updated first.
             $table->string('tracking_stage')->nullable(false)->default('Approved')->change();
        });
    }
};