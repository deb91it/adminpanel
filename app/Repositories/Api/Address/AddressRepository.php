<?php namespace App\Repositories\Address;

/**
 * Interface AddressRepository
 * @package App\Repositories\Address
 */
interface AddressRepository
{
    /**
     * @return mixed
     */
    public function getAllCountries();
    /**
     * @return mixed
     */
    public function getAllZones();

    /**
     * @param $country_id
     * @return mixed
     */
    public function getZoneByCountryId($country_id);
}
