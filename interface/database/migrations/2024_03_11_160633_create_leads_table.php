<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // We create a table only for development.
        // In production, this table exists (possibly)
        if(App::isProduction()) {
            return;
        }

        Schema::connection('mysql_lc')->create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('aff_network_name');
            $table->string('conversion_status');
            $table->unsignedBigInteger('t_id');
            $table->string('click_id');
            $table->string('name');
            $table->string('phone');
            $table->string('unique_id');
            $table->string('country_code');
            $table->string('offer_id');
            $table->string('offer_name');
            $table->string('product');
            $table->boolean('is_sent');
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
        if(App::isProduction()) {
            return;
        }

        Schema::connection('mysql_lc')->dropIfExists('leads');
    }
}
