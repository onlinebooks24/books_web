<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('user_id');
            $table->text('body');
            $table->integer('category_id');
            $table->string('keyword');
            $table->boolean('status');
            $table->text('meta_description');
            $table->timestamps();
            $table->string('slug');
            $table->string('series_article')->nullable();
            $table->boolean('waiting_for_approval')->default(true);
            $table->integer('count')->default(0);
            $table->text('conclusion')->nullable();
            $table->integer('thumbnail_id')->nullable();
            $table->time('sub_admin_spend_time')->default('00:00:00');
            $table->time('admin_spend_time')->default('00:00:00');
            $table->time('editor_spend_time')->default('00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
