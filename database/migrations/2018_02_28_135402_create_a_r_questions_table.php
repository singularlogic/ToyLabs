<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateARQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ar_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->integer('ar_model_id')->unsigned();
            $table->timestamps();

            $table->foreign('ar_model_id')->references('id')->on('ar_models')->onDelete('cascade');
        });

        Schema::create('ar_question_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('ar_question_id')->unsigned();
            $table->integer('value')->default(3);

            $table->index(['user_id', 'ar_question_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ar_question_id')->references('id')->on('ar_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ar_question_answers');
        Schema::dropIfExists('ar_questions');
    }
}
