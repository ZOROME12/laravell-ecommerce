<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['order_id']);

            // Change order_id from integer to string
            $table->string('order_id', 30)->change();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Change back to integer
            $table->integer('order_id')->change();

            // Optionally, recreate the foreign key
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }
};
