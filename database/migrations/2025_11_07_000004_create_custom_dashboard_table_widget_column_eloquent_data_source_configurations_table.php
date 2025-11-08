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
        Schema::create('custom_dashboard_table_widget_column_eloquent_data_source_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('attribute');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_dashboard_table_widget_column_eloquent_data_source_configurations');
    }
};
