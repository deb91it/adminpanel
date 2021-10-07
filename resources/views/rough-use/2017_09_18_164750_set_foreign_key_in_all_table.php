<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetForeignKeyInAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     */
    public function up()
    {
        Schema::create('permissions', function ($table) {
            $table->foreign('permission_group_id')->references('id')->on('permission_groups')->onDelete('cascade');
        });

        Schema::create('role_permissions', function ($table) {
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('zones', function($table)
        {
            $table->foreign('country_id')->references('country_id')->on('countries');
        });

        Schema::create('members', function ($table) {
            $table->foreign('model_id')->references('id')->on('models');
        });

        Schema::table('role_member', function($table)
        {

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('member_id')->references('id')->on('members');
        });

        Schema::create('admin_users', function($table)
        {
            $table->foreign('member_id')->references('id')->on('members');
        });

        Schema::create('member_tokens', function($table)
        {
            $table->foreign('member_id')->references('id')->on('members');
        });

        Schema::create('merchants', function($table)
        {
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('zone_id')->references('zone_id')->on('zones');
        });

        Schema::create('passengers', function(Blueprint $table)
        {
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('zone_id')->references('zone_id')->on('zones');
        });

        Schema::create('drivers', function(Blueprint $table)
        {
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('zone_id')->references('zone_id')->on('zones');
        });

        Schema::create('driving_license', function(Blueprint $table)
        {
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });

        Schema::create('national_id_cards', function(Blueprint $table)
        {
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });

        Schema::create('vehicles', function(Blueprint $table)
        {
            $table->foreign('category_id')->references('id')->on('vehicle_categories');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('vehicle_make_id')->references('id')->on('vehicle_makes');

        });

        Schema::create('device_registration_number', function(Blueprint $table)
        {
            $table->foreign('member_id')->references('id')->on('members');
        });


        Schema::create('notifications', function(Blueprint $table)
        {
            $table->foreign('notification_type_id')->references('id')->on('notification_types');
            $table->foreign('member_id')->references('id')->on('members');
        });

        Schema::create('member_profile_progress', function(Blueprint $table)
        {
            $table->foreign('profile_progress_id')->references('id')->on('profile_progress');
        });

        Schema::create('gps_coordinates', function(Blueprint $table)
        {
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vehicle_type')->references('id')->on('vehicle_categories');
        });

        Schema::create('trips', function(Blueprint $table)
        {
            $table->foreign('request_id')->references('id')->on('trip_requests');
            $table->foreign('passenger_id')->references('id')->on('passengers');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });

        Schema::create('trip_payment_method', function(Blueprint $table)
        {
            $table->foreign('trip_id')->references('id')->on('trips');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
        });

        Schema::create('passenger_transaction_logs', function(Blueprint $table)
        {
            $table->foreign('passenger_id')->references('id')->on('passengers');
        });

        Schema::create('driver_transaction_logs', function(Blueprint $table)
        {
            $table->foreign('driver_id')->references('id')->on('passengers');
        });

        Schema::create('trip_requests', function(Blueprint $table)
        {
            $table->foreign('passenger_id')->references('id')->on('passengers');
            $table->foreign('vehicle_type')->references('id')->on('vehicle_categories');
        });

        Schema::create('trip_request_response', function(Blueprint $table)
        {
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('request_id')->references('id')->on('trip_requests');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });

        Schema::create('vehicle_registrations', function(Blueprint $table)
        {
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });

        Schema::create('vehicle_insurances', function(Blueprint $table)
        {
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });

        Schema::create('vehicle_tax_tokens', function(Blueprint $table)
        {
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });

        Schema::create('vehicle_fitness_certificates', function(Blueprint $table)
        {
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });

        Schema::create('vehicle_objection_certificates', function(Blueprint $table)
        {
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->foreign('member_id')->references('id')->on('members');
        });

        Schema::create('referral_codes', function(Blueprint $table)
        {
            $table->foreign('passenger_id')->references('id')->on('passengers');
            $table->foreign('parent_id')->references('id')->on('passengers');
        });

        Schema::create('promo_code_passenger', function (Blueprint $table) {
            $table->foreign('promo_code_id')->references('id')->on('promo_codes');
            $table->foreign('passenger_id')->references('id')->on('passengers');
        });

        Schema::create('trip_request_notifications', function(Blueprint $table)
        {
            $table->foreign('request_id')->references('id')->on('trip_requests');
            $table->foreign('passenger_id')->references('id')->on('passengers');
            $table->foreign('driver_id')->references('id')->on('drivers');
        });

        Schema::create('trip_reviews', function(Blueprint $table)
        {
            $table->foreign('trip_id')->references('id')->on('trips');
            $table->integer('passenger_id')->unsigned()->default(0);
            $table->integer('driver_id')->unsigned()->default(0);
            $table->foreign('request_id')->references('id')->on('trip_requests');
        });

        Schema::create('national_id_back_part', function(Blueprint $table)
        {
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });

        Schema::create('driver_active_vehicle', function (Blueprint $table) {
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });

        Schema::create('drivers_vehicles', function(Blueprint $table)
        {
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });

        Schema::table('week_settles', function($table) {
            $table->foreign('merchant_id')->references('id')->on('merchants');
        });

        Schema::create('passenger_favourite_places', function(Blueprint $table)
        {
            $table->foreign('passenger_id')->references('id')->on('passengers');
        });

        Schema::create('trip_details', function(Blueprint $table)
        {
            $table->foreign('trip_id')->references('id')->on('trips');
        });

        Schema::create('driver_on_off_history', function (Blueprint $table) {
            $table->foreign('driver_id')->references('id')->on('drivers');
        });

        Schema::create('driver_block_status', function (Blueprint $table) {
            $table->foreign('driver_id')->references('id')->on('drivers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //2017_04_04_112317_create_vehicle_fitness_certificates_table.php.php
        //ALTER TABLE week_settles MODIFY week_started_at TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00';
        //SET sql_mode = '';
        //SET sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
    }
}
