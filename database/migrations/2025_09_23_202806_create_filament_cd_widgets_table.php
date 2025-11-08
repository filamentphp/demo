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
        Schema::create('filament_cd_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dashboard_id')->constrained('filament_cd_dashboards')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->text('thumbnail_image')->nullable();
            $table->string('type')->nullable();
            $table->nullableMorphs('config', indexName: 'filament_cd_widgets_config_index');
            $table->unsignedInteger('sort');
            $table->jsonb('column_span');
            $table->timestamp('enabled_at')->nullable()->useCurrent();
            $table->foreignId('previous_version_id')->nullable()->constrained('filament_cd_widgets', indexName: 'filament_cd_widget_previous_version_id_foreign')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filament_cd_widgets');
    }
};
