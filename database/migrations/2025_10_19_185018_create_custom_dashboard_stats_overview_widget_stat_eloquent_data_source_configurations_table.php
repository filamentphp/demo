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
        Schema::create('custom_dashboard_stats_overview_widget_stat_eloquent_data_source_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('operator');
            $table->string('attribute')->nullable();
            $table->jsonb('filters')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_dashboard_stats_overview_widget_stat_eloquent_data_source_configurations');
    }
};
