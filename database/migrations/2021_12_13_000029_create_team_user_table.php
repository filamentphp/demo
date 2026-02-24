<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);
        });

        // Seed: assign all existing users to all existing teams
        $users = DB::table('users')->pluck('id');
        $teams = DB::table('teams')->pluck('id');

        foreach ($users as $userId) {
            foreach ($teams as $teamId) {
                DB::table('team_user')->insert([
                    'team_id' => $teamId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
