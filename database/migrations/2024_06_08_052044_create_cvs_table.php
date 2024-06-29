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
        // title(tencv) user_id template_id  offset text_font text_size theme_color, line-width?,
// name position phone address birth email avatar
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('user_id');
            $table->integer('template_id');
            $table->string('offset')->nullable();
            $table->integer('text_font')->nullable();
            $table->integer('text_size')->nullable();
            $table->integer('theme_color')->nullable();
            $table->string('name');
            $table->string('position');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('birthday');
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cvs');
    }
};
