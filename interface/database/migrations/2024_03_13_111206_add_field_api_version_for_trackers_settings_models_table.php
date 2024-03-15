<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldApiVersionForTrackersSettingsModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_lc')->table('trackers_settings_models', function (Blueprint $table) {
            $table->string('api_version', 10)
                ->default('v1')
                ->after('t_api_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_lc')
            ->dropColumns('trackers_settings_models', 'api_version');
    }
}
