<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('id');
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dropColumn('session_id');
        });
    }
};
