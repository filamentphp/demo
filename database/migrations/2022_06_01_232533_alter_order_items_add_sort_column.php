<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->unsignedInteger('sort')->default(0)->after('id');
        });
    }

    public function down()
    {
        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
};
