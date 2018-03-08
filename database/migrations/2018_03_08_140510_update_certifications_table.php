<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certifications', function (Blueprint $table) {
            $table->string('description')->nullable();
        });

        Schema::table('certification_organization', function (Blueprint $table) {
            $table->boolean('is_verified')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certifications', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('certification_organization', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
}
