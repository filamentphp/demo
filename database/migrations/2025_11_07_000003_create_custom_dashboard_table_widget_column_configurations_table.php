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
        Schema::create('custom_dashboard_table_widget_column_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_widget_configuration_id')->constrained('custom_dashboard_table_widget_configurations')->cascadeOnDelete();
            $table->nullableMorphs('data_source_configuration');
            $table->string('label');
            $table->boolean('is_searchable')->default(false);
            $table->boolean('is_sortable')->default(false);
            $table->boolean('is_toggleable')->default(false);
            $table->unsignedInteger('sort');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_dashboard_table_widget_column_configurations');
    }
};
