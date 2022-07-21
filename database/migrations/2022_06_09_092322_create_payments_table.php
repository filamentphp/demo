<?php

use App\Models\Shop\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('shop_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class);

            $table->string('reference');

            $table->string('provider');

            $table->string('method');

            $table->decimal('amount');

            $table->string('currency');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_payments');
    }
};
