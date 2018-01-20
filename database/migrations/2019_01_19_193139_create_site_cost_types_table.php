<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteCostTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //Please active it
    public function up()
    {
        Schema::create('site_cost_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('site_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->integer('site_cost_type_id');
            $table->integer('amount')->nullable();
            $table->dateTime('when_paid');
            $table->integer('article_id')->nullable();
            $table->integer('user_id')->default(1);
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
        Schema::dropIfExists('site_cost_types');
        Schema::dropIfExists('site_costs');
    }
}
