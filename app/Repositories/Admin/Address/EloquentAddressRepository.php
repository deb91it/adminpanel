<?php namespace App\Repositories\Admin\Address;


use App\DB\Admin\Country;
use App\DB\Admin\Zone;
use App\DB\Language;

class EloquentAddressRepository implements AddressRepository
{
    /**
     * @var Role
     */
    protected $language;
    protected $country;
    protected $zone;

    /**
     * @param Role $role
     */
    function __construct(Language $language, Country $country, Zone $zone)
    {
        $this->language = $language;
        $this->country = $country;
        $this->zone = $zone;
    }

    /**
     * @return mixed
     */
    public function getAllCountries()
    {
        return  ['' => 'Select Country'] + $this->country->where('status', 1)->orderBy('country_id', 'asc')->lists('name', 'country_id')->toArray();
    }
    /**
     * @return mixed
     */
    public function getAllZones()
    {

    }

    /**
     * @param $country_id
     * @return mixed
     */
    public function getZoneByCountryId($country_id)
    {
        return $this->zone->where(['country_id'=> $country_id, 'status'=> 1])->orderBy('zone_id', 'asc')->get();
    }

    public function getLanguageList()
    {
        return  ['' => 'Select Language'] + $this->language->where('status', 1)->orderBy('id', 'asc')->lists('language_name', 'id')->toArray();
    }

    /**
     * @param $country_id
     * @return mixed
     */
    public function getZoneListByCountryId($country_id)
    {
        return ['' => 'Select City'] + $this->zone->where(['country_id'=> $country_id, 'status'=> 1])->orderBy('zone_id', 'asc')->lists('name', 'zone_id')->toArray();
    }
}
