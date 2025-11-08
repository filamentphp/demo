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
        Schema::create('filament_cd_table_widget_column_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_widget_config_id')->constrained('filament_cd_table_widget_configs', indexName: 'filament_cd_table_widget_config_id_foreign')->cascadeOnDelete();
            $table->nullableMorphs('data_source_config', indexName: 'filament_cd_table_widget_columns_data_source_config_index');
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
        Schema::dropIfExists('filament_cd_table_widget_column_configs');
    }
};
