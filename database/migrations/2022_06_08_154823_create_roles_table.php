<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();

            $table->string('slug');
            $table->string('title');

            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Role::class);
            $table->foreignIdFor(User::class);
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }
};
