<?php namespace App\Repositories\Admin\Address;

/**
 * Interface AddressRepository
 * @package App\Repositories\Address
 */
/**
 * Interface AddressRepository
 * @package App\Repositories\Admin\Address
 */
/**
 * Interface AddressRepository
 * @package App\Repositories\Admin\Address
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

    /**
     * @return mixed
     */
    public function getLanguageList();

    /**
     * @param $country_id
     * @return mixed
     */
    public function getZoneListByCountryId($country_id);
}
