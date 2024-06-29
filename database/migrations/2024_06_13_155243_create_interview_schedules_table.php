<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterviewSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('apply_id');
            $table->datetime('time');
            $table->string('type');
            $table->string('location')->nullable();
            $table->string('link')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interview_schedules');
    }
}
