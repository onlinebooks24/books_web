<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //Please active it
    public function up()
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_number');
            $table->string('title')->nullable();
            $table->integer('product_id')->nullable();
            $table->dateTime('shipment_date');
            $table->float('ad_fees')->default(0);
            $table->boolean('manually_inserted_on_article')->default(false); // suppose we have not included a product
            // in the python article. but user has bought is. we will manually insert this product on the article.
            $table->integer('article_id')->nullable();
            $table->integer('product_type')->default(1);
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
        Schema::dropIfExists('product_orders');

    }
}
