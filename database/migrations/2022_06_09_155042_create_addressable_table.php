<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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

            // Determine database type and use appropriate syntax for generated columns
            if (DB::getDriverName() === 'pgsql') {
                // PostgreSQL requires stored columns with || for concatenation
                $table->string('full_address')->storedAs("street || ', ' || zip || ' ' || city");
            } elseif (DB::getDriverName() === 'sqlite') {
                // SQLite uses || for concatenation, virtualAs is used
                $table->string('full_address')->virtualAs("street || ', ' || zip || ' ' || city");
            } else {
                // MySQL uses CONCAT for string concatenation
                $table->string('full_address')->virtualAs("CONCAT(street, ', ', zip, ' ', city)");
            }

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
