<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulerJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //Please active it
    public function up()
    {
        Schema::create('notification_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('scheduler_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task_description');
            $table->text('short_message')->nullable();
            $table->boolean('task_completed')->default(false);
            $table->integer('notification_interval')->default(24);
            $table->integer('notification_type_id')->default(1);
            $table->dateTime('last_notification')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->integer('article_id')->nullable();
            $table->string('notification_status')->nullable();
            $table->string('transaction_no')->nullable();
            $table->integer('count')->default(0);
            $table->integer('user_id')->nullable();
            $table->string('phone_no')->nullable();
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
        Schema::dropIfExists('notification_types');
        Schema::dropIfExists('scheduler_jobs');
    }
}
