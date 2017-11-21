<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissingPivotTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('awards', function (Blueprint $table) {
            $table->string('url')->nullable();
        });

        Schema::create('certification_organization', function (Blueprint $table) {
            $table->integer('certification_id')->unsigned();
            $table->integer('organization_id')->unsigned();
            $table->timestamp('certified_at');
        });

        Schema::create('award_organization', function (Blueprint $table) {
            $table->integer('award_id')->unsigned();
            $table->integer('organization_id')->unsigned();
            $table->timestamp('awarded_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('awards', function (Blueprint $table) {
            $table->dropColumn('url');
        });
        Schema::drop('award_organization');
        Schema::drop('certification_organization');
    }
}
