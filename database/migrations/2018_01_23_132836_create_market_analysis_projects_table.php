<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketAnalysisProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_analysis_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('anlzer_project_id')->unsigned();
            $table->text('anlzer_data')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('product_id')->unsigned();
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
        Schema::dropIfExists('market_analysis_projects');
    }
}
