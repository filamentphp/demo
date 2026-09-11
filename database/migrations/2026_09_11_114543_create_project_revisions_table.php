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
        Schema::table('projects', function (Blueprint $table): void {
            $table->unsignedInteger('description_version')->default(0);
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
        });
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
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('thread_id')->nullable()->constrained('page_messages')->nullOnDelete();
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_revisions');
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('owner_id');
            $table->dropColumn('description_version');
        });
    }
};
