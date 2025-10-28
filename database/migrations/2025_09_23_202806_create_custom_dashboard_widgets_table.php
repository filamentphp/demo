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
        Schema::create('custom_dashboard_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_dashboard_id')->constrained()->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->text('thumbnail_image')->nullable();
            $table->string('type')->nullable();
            $table->nullableMorphs('configuration');
            $table->unsignedInteger('sort');
            $table->jsonb('column_span');
            $table->timestamp('enabled_at')->nullable()->useCurrent();
            $table->foreignId('previous_version_id')->nullable()->constrained('custom_dashboard_widgets')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_dashboard_widgets');
    }
};
