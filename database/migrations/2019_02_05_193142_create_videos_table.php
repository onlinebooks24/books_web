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
            $table->text('book_title_html')->nullable();
            $table->text('book_image_html')->nullable();
            $table->text('book_description_html')->nullable();
            $table->text('book_conclusion_html')->nullable();
            $table->string('background_image')->nullable();
            $table->string('audio_name')->nullable();
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id');
            $table->integer('video_template_id');
            $table->string('video_name');
            $table->string('youtube_link')->nullable();
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
