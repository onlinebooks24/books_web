<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveWritterOnUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->boolean('active_writer')->default(false);
        });

        Schema::table('articles', function($table) {
            $table->datetime('article_deadline')->default('2017-01-01 01:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_types');

        Schema::table('users', function($table) {
            $table->dropColumn('active_writer');
        });
    }
}
