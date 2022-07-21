<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('country')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            $table->string('full_address')->virtualAs(
                config('database.default') === 'sqlite'
                    ? "street  || ', ' || zip  || ' ' || city"
                    : "CONCAT(street, ', ', zip, ' ', city)"
            );

            $table->timestamps();
        });

        Schema::create('addressables', function (Blueprint $table) {
            $table->foreignId('address_id');
            $table->morphs('addressable');
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('addressables');
    }
};
