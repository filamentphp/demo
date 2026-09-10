<?php

use App\Models\HR\Project;
use App\Models\PageMessage;
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
        Schema::table('page_messages', function (Blueprint $table): void {
            $table->string('body_format')->default('text');
            $table->foreignId('activity_id')->nullable()->unique()->constrained('project_activities')->cascadeOnDelete();
            $table->timestamp('resolved_at')->nullable();
        });
        Schema::create('page_message_reads', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('page_message_id')->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'page_message_id']);
        });

        foreach (Project::withTrashed()->cursor() as $project) {
            PageMessage::query()->where('room', hash('sha256', '/projects/' . $project->id . '/edit'))
                ->update(['room' => hash('sha256', '/projects/' . $project->id)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_message_reads');
        Schema::table('page_messages', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('activity_id');
            $table->dropColumn(['body_format', 'resolved_at']);
        });
    }
};
