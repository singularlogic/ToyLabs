<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('value')->unsigned();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('ages');
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ages');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('min_age');
            $table->dropColumn('max_age');
            $table->integer('ages')->nullable();
        });
    }
}
