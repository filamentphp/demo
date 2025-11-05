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
        Schema::create('custom_dashboard_scatter_chart_widget_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->string('data_source');
            $table->string('data_label')->nullable();
            $table->string('data_color')->nullable();
            $table->nullableMorphs('data_source_configuration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_dashboard_scatter_chart_widget_configurations');
    }
};
