<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_links', function (Blueprint $table): void {
            $table->dropColumn('image');
        });
    }

    public function down(): void
    {
        Schema::table('blog_links', function (Blueprint $table): void {
            $table->string('image')->nullable();
        });
    }
};
