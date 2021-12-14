<?php

declare(strict_types=1);

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
        Schema::create('shop_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->enum('type', ['percentage', 'fixed_amount']);
            $table->decimal('value');
            $table->unsignedInteger('usage_limit')->nullable();
            $table->boolean('usage_limit_per_customer')->default(false);
            $table->unsignedInteger('total_use')->default(0);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('is_visible')->default(false);
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
        Schema::dropIfExists('shop_discounts');
    }
};
