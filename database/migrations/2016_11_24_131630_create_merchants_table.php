<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('first_name');
            $table->string('last_name');
            $table->tinyInteger('gender');
            $table->string('profile_pic');
            $table->string('profile_pic_url');
            $table->string('pic_mime_type', 50);
            $table->string('company_name', 100)->nullable();
            $table->integer('language_id')->unsigned()->default(0);
            $table->foreign('language_id')->references('id')->on('languages');
            $table->integer('member_id')->unsigned();
            $table->foreign('member_id')->references('id')->on('members');
            $table->integer('zone_id')->unsigned();
            $table->foreign('zone_id')->references('zone_id')->on('zones');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('merchants');
    }
}
