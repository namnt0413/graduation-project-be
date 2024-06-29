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
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('budget');
            $table->unsignedBigInteger('salary');
            $table->unsignedBigInteger('max_salary')->nullable();
            $table->string('right');
            $table->string('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('right');
            $table->dropColumn('address');
            $table->dropColumn('salary');
            $table->dropColumn('max_salary');
            $table->unsignedBigInteger('budget');
        });
    }
};
