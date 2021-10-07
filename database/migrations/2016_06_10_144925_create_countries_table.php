<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function(Blueprint $table)
        {
            $table->increments('country_id')->unsigned();
            $table->string('name', 128)->nullable(false);
            $table->string('iso_code_2', 2)->nullable(false);
            $table->string('iso_code_3', 3)->nullable(false);
            $table->text('address_format')->nullable(false);
            $table->tinyInteger('postcode_required')->nullable(false);
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
        Schema::drop('countries');
    }
}
