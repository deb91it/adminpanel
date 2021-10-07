<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function(Blueprint $table)
        {
            $table->increments('zone_id')->unsigned();
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('country_id')->on('countries');
            $table->string('name', 128)->nullable(false);
            $table->string('code', 32)->nullable(false);
            $table->tinyInteger('status')->default(1)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('zones');
    }
}
