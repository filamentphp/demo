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
        Schema::create('filament_cd_table_widget_configs', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->string('data_source');
            $table->nullableMorphs('data_source_config', indexName: 'filament_cd_table_widgets_data_source_config_index');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filament_cd_table_widget_configs');
    }
};
