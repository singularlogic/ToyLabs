<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateThreadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->nullableMorphs('target');
            $table->enum('type', ['feedback', 'negotiation'])->nullable();
            $table->boolean('locked')->default(false);
            $table->integer('organization_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('threads', function (Blueprint $table) {
            $table->dropColumn('organization_id');
            $table->dropColumn('locked');
            $table->dropColumn('type');
            $table->dropColumn('target_id');
            $table->dropColumn('target_type');
        });
    }
}
