<?php namespace App\Repositories\Address;

use DB;

class EloquentAddressRepository implements AddressRepository
{
    function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    /**
     * @return mixed
     */
    public function getAllCountries()
    {
        return DB::table('countries')
            ->select('country_id', 'name', 'iso_code_2', 'iso_code_3', 'address_format', 'postcode_required')
            ->where('status', 1)
            ->get();
    }
    /**
     * @return mixed
     */
    public function getAllZones()
    {
        return DB::table('zones as z')
            ->select('z.zone_id', 'co.country_id', 'co.name as country_name', 'z.name', 'z.code')
            ->join('countries as co', 'co.country_id', '=', 'z.country_id')
            ->where('z.status', 1)
            ->get();
    }

    /**
     * @param $country_id
     * @return mixed
     */
    public function getZoneByCountryId($country_id)
    {
        return DB::table('zones as z')
            ->select('z.zone_id', 'co.country_id', 'co.name as country_name', 'z.name', 'z.code')
            ->join('countries as co', 'co.country_id', '=', 'z.country_id')
            ->where('co.country_id', $country_id)
            ->where('z.status', 1)
            ->get();
    }
}
