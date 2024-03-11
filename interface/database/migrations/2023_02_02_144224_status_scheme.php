<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StatusScheme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_lc')->create('lead_collector_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('incoming_status_name', 200)->nullable();
            $table->string('partner_name', 220);
            $table->boolean('status_locked')->nullable();
            $table->boolean('accept_event_2')->nullable();
            $table->boolean('accept_payment')->nullable();
            $table->timestamps();

            $table->foreignId('status_id')->nullable()
                ->references('ad2lynx_statuses_id')
                ->on('ad2lynx_statuses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_collector_statuses');
    }
}
