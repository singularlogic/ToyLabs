<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->boolean('is_archived')->default(0);
        });

        Schema::table('prototypes', function (Blueprint $table) {
            $table->boolean('is_archived')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });

        Schema::table('prototypes', function (Blueprint $table) {
            $table->boolean('is_archived')->default(0);
        });
    }
}
