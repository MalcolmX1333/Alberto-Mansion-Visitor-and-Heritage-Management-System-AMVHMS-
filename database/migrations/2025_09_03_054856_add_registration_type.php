<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->boolean('registration_type')->default(0)->comment('0 for Individual, 1 for Group');
        });
    }

    public function down()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn('registration_type');
        });
    }
};
