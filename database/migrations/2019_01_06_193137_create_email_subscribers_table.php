<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //Please active it
    public function up()
    {
        Schema::create('email_subscribers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name')->nullable();
            $table->string('email');
            $table->boolean('subscribe')->default(false);
            $table->boolean('temporary')->default(false);
            $table->integer('click_count')->default(0);
            $table->integer('email_count')->default(0);
            $table->string('source');
            $table->dateTime('last_send_email')->nullable();

            $table->timestamps();
        });

        Schema::create('email_subscriber_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('email_subscriber_id');
            $table->integer('category_id');

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
        Schema::dropIfExists('email_subscribers');
        Schema::dropIfExists('email_subscribers_categories');

    }
}
