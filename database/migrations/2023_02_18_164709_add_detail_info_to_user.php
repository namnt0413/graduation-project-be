<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->date('birthday')->nullable();
            $table->string('favourite')->nullable();
            $table->string('skill')->nullable();
            $table->string('school')->nullable();
            $table->string('work_exp')->nullable();
            $table->string('activity')->nullable();
            $table->string('prize')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('birthday');
            $table->dropColumn('favourite');
            $table->dropColumn('skill');
            $table->dropColumn('school');
            $table->dropColumn('work_exp');
            $table->dropColumn('activity');
            $table->dropColumn('prize');
        });
    }
};
