<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketAnalysisAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_analysis_analyses', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->enum('type', ['trend','social']);
                    $table->integer('anlzer_analysis_id')->unsigned();
                    $table->integer('anlzer_project_id')->unsigned();
                    $table->integer('product_id')->unsigned();
                    $table->text('anlzer_data')->nullable();
                    $table->text('data')->nullable();
                    $table->text('settings')->nullable();
                    $table->boolean('is_public')->default(false);
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
        Schema::dropIfExists('market_analysis_analyses');
    }
}
