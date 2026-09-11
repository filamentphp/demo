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
        Schema::create('page_messages', function (Blueprint $table): void {
            $table->id();
            $table->string('room', 64)->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('page_messages')->cascadeOnDelete();
            $table->text('body')->nullable();
            $table->string('body_format')->default('text');
            $table->foreignId('activity_id')->nullable()->unique()->constrained('project_activities')->cascadeOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('is_agent')->default(false);
            $table->foreignId('agent_request_id')->nullable()->unique()->constrained('page_messages')->nullOnDelete();
            $table->string('agent_status')->nullable();
            $table->boolean('agent_paused')->default(false);
            $table->string('project_field')->nullable();
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_messages');
    }
};
