<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketAnalysisConceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_analysis_concepts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('anlzer_concept_id')->unsigned();
            $table->integer('anlzer_project_id')->unsigned();
            $table->json('anlzer_data')->nullable();
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
        Schema::dropIfExists('market_analysis_concepts');
    }
}
