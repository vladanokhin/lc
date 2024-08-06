<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_lc')->create('offer_tracker', function (Blueprint $table) {
            $table->foreignId('offer_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('tracker_id')
                  ->constrained('trackers_settings_models')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql_lc')->dropIfExists('offer_tracker');
    }
}
