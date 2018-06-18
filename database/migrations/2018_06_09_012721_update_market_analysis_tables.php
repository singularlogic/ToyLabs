<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMarketAnalysisTables extends Migration
{
    /**
     * UpdateMarketAnalysisTables constructor.
     * This is just a hack to overcome the limitation of Laravel when there is an Enum column:
     * Renaming columns in a table with a enum column is not currently supported.
     * https://stackoverflow.com/questions/33140860/laravel-5-1-unknown-database-type-enum-requested
     */
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // There's an issue with Doctrine/dbal preventing the change of text column length so here's a workaround
        // Create a new column, copy data, remove old column, rename new column
        // https://github.com/doctrine/dbal/issues/2566
        // https://github.com/laravel/framework/issues/21847

        Schema::table('market_analysis_analyses', function (Blueprint $table) {
            $table->longText('anlzer_data_')->nullable()->after('anlzer_data');
            $table->longText('data_')->nullable()->after('data');
            $table->longText('settings_')->nullable()->after('settings');
        });
        DB::statement('update market_analysis_analyses set anlzer_data_ = anlzer_data, data_ = data, settings_ = settings;');
        Schema::table('market_analysis_analyses', function (Blueprint $table) {
            $table->dropColumn('anlzer_data');
            $table->dropColumn('data');
            $table->dropColumn('settings');
            $table->renameColumn('anlzer_data_', 'anlzer_data');
            $table->renameColumn('data_', 'data');
            $table->renameColumn('settings_', 'settings');
        });

        Schema::table('market_analysis_concepts', function (Blueprint $table) {
            $table->longText('anlzer_data_')->nullable()->after('anlzer_data');
        });
        DB::statement('update market_analysis_concepts set anlzer_data_ = anlzer_data;');
        Schema::table('market_analysis_concepts', function (Blueprint $table) {
            $table->dropColumn('anlzer_data');
            $table->renameColumn('anlzer_data_', 'anlzer_data');
        });

        Schema::table('market_analysis_retrievers', function (Blueprint $table) {
            $table->longText('anlzer_data_')->nullable()->after('anlzer_data');
        });
        DB::statement('update market_analysis_retrievers set anlzer_data_ = anlzer_data;');
        Schema::table('market_analysis_retrievers', function (Blueprint $table) {
            $table->dropColumn('anlzer_data');
            $table->renameColumn('anlzer_data_', 'anlzer_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_analysis_analyses', function (Blueprint $table) {
            $table->text('anlzer_data_')->nullable()->after('anlzer_data');
            $table->text('data_')->nullable()->after('data');
            $table->text('settings_')->nullable()->after('settings');
        });
        DB::statement('update market_analysis_analyses set anlzer_data_ = anlzer_data, data_ = data, settings_ = settings;');
        Schema::table('market_analysis_analyses', function (Blueprint $table) {
            $table->dropColumn('anlzer_data');
            $table->dropColumn('data');
            $table->dropColumn('settings');
            $table->renameColumn('anlzer_data_', 'anlzer_data');
            $table->renameColumn('data_', 'data');
            $table->renameColumn('settings_', 'settings');
        });

        Schema::table('market_analysis_concepts', function (Blueprint $table) {
            $table->text('anlzer_data_')->nullable()->after('anlzer_data');
        });
        DB::statement('update market_analysis_concepts set anlzer_data_ = anlzer_data;');
        Schema::table('market_analysis_concepts', function (Blueprint $table) {
            $table->dropColumn('anlzer_data');
            $table->renameColumn('anlzer_data_', 'anlzer_data');
        });

        Schema::table('market_analysis_retrievers', function (Blueprint $table) {
            $table->text('anlzer_data_')->nullable()->after('anlzer_data');
        });
        DB::statement('update market_analysis_retrievers set anlzer_data_ = anlzer_data;');
        Schema::table('market_analysis_retrievers', function (Blueprint $table) {
            $table->dropColumn('anlzer_data');
            $table->renameColumn('anlzer_data_', 'anlzer_data');
        });
    }
}
