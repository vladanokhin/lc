<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_lc')->create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('geo');
            $table->string('language');
            $table->string('type');
            $table->string('category');
            $table->string('form_factor');
            $table->integer('lp_numbering');
            $table->string('name');
            $table->string('aff_network');
            $table->integer('price');
            $table->string('offer_type');
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
        Schema::connection('mysql_lc')->dropIfExists('offers');
    }
}
