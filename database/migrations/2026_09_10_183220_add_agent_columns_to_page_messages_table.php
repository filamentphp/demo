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
        Schema::table('page_messages', function (Blueprint $table): void {
            $table->boolean('is_agent')->default(false);
            $table->foreignId('agent_request_id')->nullable()->unique()->constrained('page_messages')->nullOnDelete();
            $table->string('agent_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_messages', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('agent_request_id');
            $table->dropColumn(['is_agent', 'agent_status']);
        });
    }
};
