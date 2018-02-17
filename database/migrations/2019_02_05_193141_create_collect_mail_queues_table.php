<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectMailQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //Please active it
    public function up()
    {
        Schema::create('collect_mail_queues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('topic');
            $table->integer('category_id');
            $table->string('article_id');
            $table->boolean('run_cron_job');
            $table->integer('limit_cron_job_attempt');
            $table->text('custom_mail_template')->nullable();
            $table->integer('run_count');
            $table->dateTime('last_time_run')->nullable();
            $table->timestamps();
        });

        Schema::table('email_subscribers', function (Blueprint $table) {
            $table->integer('collect_mail_queue_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collect_mail_queues');

        Schema::table('email_subscribers', function($table) {
            $table->dropColumn('collect_mail_queue_id');
        });
    }
}
