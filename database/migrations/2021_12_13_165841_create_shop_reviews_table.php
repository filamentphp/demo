<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_customer_id')->nullable()->constrained();
            $table->foreignId('shop_product_id')->nullable()->constrained();
            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->integer('rating');
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_recommended')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_reviews');
    }
};
