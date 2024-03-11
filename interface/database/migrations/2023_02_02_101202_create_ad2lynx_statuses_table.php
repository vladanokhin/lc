<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAd2lynxStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_lc')->create('ad2lynx_statuses', function (Blueprint $table) {
            $table->id('ad2lynx_statuses_id');
            $table->string('status_category')->unique()->index();
            $table->tinyInteger('weight');
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
        Schema::dropIfExists('ad2lynx_statuses');
    }
}
