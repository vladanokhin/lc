<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerProvidersModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_lc')->create('lead_collector_partners_settings', function (Blueprint $table) {
            $table->string('partner_name', 200)->unique();
            $table->string('partner_provider', 200)->unique();
            $table->string('provider_class', 200);
            $table->string('api_key')->nullable();
            $table->string('endpoint');
            $table->id();
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
        Schema::dropIfExists('lead_collector_partners_settings');
    }
}
