<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('isVisited')->default(false);
        });
    }

    public function down()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn('isVisited');
        });
    }
};
