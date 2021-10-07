<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 50)->unique()->nullable();
            $table->string('email', 150)->unique()->nullable();
            $table->string('mobile_no', 14)->unique();
            $table->string('password');
            $table->string('salt', 20);
            $table->integer('model_id')->unsigned()->comment('1 = Admin, 2 = Passenger, 3 = Driver, 4 = Merchant');
            $table->foreign('model_id')->references('id')->on('models');
            $table->bigInteger('facebook_id')->nullable();
            $table->bigInteger('google_id')->nullable();
            $table->tinyInteger('can_login')->default(0);
            $table->string('activation_code');
            $table->dateTime('activation_code_expire');
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('user_type')->default(0)->comment('0 = Admin, 1 = Passenger, 2 = Driver, 3 = Merchant');
            $table->tinyInteger('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('members');
    }

}
