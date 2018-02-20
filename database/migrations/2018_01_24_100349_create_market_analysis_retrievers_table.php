<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketAnalysisRetrieversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_analysis_retrievers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('anlzer_retriever_id')->unsigned();
            $table->integer('organization_id')->nullable();
            $table->text('anlzer_data')->nullable();
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
        Schema::dropIfExists('market_analysis_retrievers');
    }
}
