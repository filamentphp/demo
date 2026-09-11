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
        Schema::create('project_revisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('open_project_id')->nullable()->unique()->constrained('projects')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('requested_reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('review_requested_at')->nullable();
            $table->enum('status', ['pending', 'changes_requested', 'applied', 'rejected'])->default('pending');
            $table->text('reason');
            $table->text('feedback')->nullable();
            $table->json('base_values');
            $table->json('proposed_values');
            $table->json('base_tasks')->nullable();
            $table->json('proposed_tasks')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('thread_id')->nullable()->constrained('page_messages')->nullOnDelete();
            $table->foreignId('source_message_id')->nullable()->constrained('page_messages')->nullOnDelete();
            $table->foreignId('rollback_of_id')->nullable()->constrained('project_revisions')->nullOnDelete();
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
        });
        Schema::table('project_activities', function (Blueprint $table): void {
            $table->foreignId('revision_id')->nullable()->constrained('project_revisions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_activities', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('revision_id');
        });
        Schema::dropIfExists('project_revisions');
    }
};
