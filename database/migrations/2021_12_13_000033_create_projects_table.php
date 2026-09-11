<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('description_state')->nullable();
            $table->unsignedInteger('description_version')->default(0);
            $table->string('color')->nullable();
            $table->string('status')->default('planning');
            $table->string('priority')->default('medium');
            $table->decimal('budget', 12, 2)->default(0);
            $table->decimal('spent', 12, 2)->default(0);
            $table->decimal('estimated_hours', 8, 1)->default(0);
            $table->decimal('actual_hours', 8, 1)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->json('plan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
