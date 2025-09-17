<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->boolean('is_main')->default(false)->after('product_id');
        });
    }

    public function down()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('is_main');
        });
    }
};
