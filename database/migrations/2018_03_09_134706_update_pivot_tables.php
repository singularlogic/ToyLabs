<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePivotTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certification_organization', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::table('award_organization', function (Blueprint $table) {
            $table->increments('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certification_organization', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('award_organization', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
