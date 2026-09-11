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
        Schema::table('project_revisions', function (Blueprint $table): void {
            $table->json('base_tasks')->nullable();
            $table->json('proposed_tasks')->nullable();
            $table->foreignId('source_message_id')->nullable()->constrained('page_messages')->nullOnDelete();
            $table->foreignId('rollback_of_id')->nullable()->constrained('project_revisions')->nullOnDelete();
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
        Schema::table('project_revisions', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('source_message_id');
            $table->dropConstrainedForeignId('rollback_of_id');
            $table->dropColumn(['base_tasks', 'proposed_tasks']);
        });
    }
};
