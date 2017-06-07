<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_types', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('role_id')->unsigned();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('legal_name');
            $table->string('reg_country')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('legal_form')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('address')->nullable();
            $table->string('po_box')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('website_url')->nullable();
            $table->text('description')->nullable();
            $table->integer('owner_id')->unsigned();
            $table->integer('organization_type_id')->unsigned();
            $table->integer('gitlab_group_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('organization_type_id')->references('id')->on('organization_types');
        });

        Schema::create('organization_has_users', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('organization_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('organization_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_has_users');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('organization_types');
    }
}
