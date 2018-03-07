<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollaborationRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaboration_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('collaboration_id')->unsigned();
            $table->morphs('collaborator');
            $table->integer('organization_id')->unsigned();
            $table->boolean('is_pending')->default(1);
            $table->integer('rating_1');
            $table->integer('rating_2');
            $table->integer('rating_3');
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
        Schema::dropIfExists('collaboration_ratings');
    }
}
