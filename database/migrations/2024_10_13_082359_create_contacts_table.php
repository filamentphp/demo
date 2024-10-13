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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('nom');              // Name
            $table->string('email');            // Email
            $table->text('message');            // Message
            $table->boolean('entreprise')->default(false); // Checkbox for "Are you an enterprise?"
            $table->string('telephone')->nullable();       // Phone (optional)
            $table->string('ville')->nullable();           // City (optional)
            $table->string('nom_entreprise')->nullable();  // Enterprise Name (optional)
            $table->string('num_pattente')->nullable();    // Registration number (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
