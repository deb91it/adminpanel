<?php namespace App\Repositories\Api\UtilsApi;

interface UtilsApiRepository
{
    public function getActivePaymentMethods();
    public function getSiteSettings($driver_id, $vehicle_id);
    public function getAppSettings($key);
    public function postDriverOnOff($driver_id, $value);
    public function getDefaultSettings();
    public function getDefaultSettingsDev();
    public function getDriverPromotion();
    public function getEmergencyVehicleInCity($inputs);
}
