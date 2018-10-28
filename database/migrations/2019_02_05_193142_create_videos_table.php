<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //Please active it
    public function up()
    {
        Schema::create('videos_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_name');
            $table->integer('introduction');
            $table->integer('end');
            $table->integer('book_picture');
            $table->integer('book_description');
            $table->string('background_image');
            $table->string('audio_location');
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id');
            $table->integer('video_template_id');
            $table->string('file_location');
            $table->string('youtube_link');
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
        Schema::dropIfExists('videos_templates');
        Schema::dropIfExists('videos');
    }
}
