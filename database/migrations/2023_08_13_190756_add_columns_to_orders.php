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
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->string('town')->nullable();
            $table->string('county')->nullable();
            $table->string('delivery')->nullable();
            $table->string('delivery_instruction')->nullable();
            $table->string('deliverylocation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn('town');
            $table->dropColumn('deliverylocation');
            $table->dropColumn('delivery_instruction');
            $table->dropColumn('delivery');
            $table->dropColumn('county');

        });
    }
};
