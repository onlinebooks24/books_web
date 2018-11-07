<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('isbn');
            $table->string('product_title');
            $table->text('product_description');
            $table->string('amazon_link');
            $table->string('image_url');
            $table->string('author_name')->nullable();
            $table->integer('article_id')->nullable();
            $table->dateTime('publication_date')->nullable();
            $table->boolen('deleted')->default(false);

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
        Schema::dropIfExists('products');
    }
}
