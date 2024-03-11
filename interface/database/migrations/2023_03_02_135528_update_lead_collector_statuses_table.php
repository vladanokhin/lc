<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLeadCollectorStatusesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::connection('mysql_lc')->table('lead_collector_statuses', function (Blueprint $table) {
      $table->renameColumn('accept_event_2', 'add_event_2');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::connection('mysql_lc')->create('lead_collector_statuses', function (Blueprint $table) {
      $table->renameColumn('add_event_2', 'accept_event_2');
    });
  }
}
