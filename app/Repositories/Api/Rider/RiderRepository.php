<?php namespace App\Repositories\Api\Rider;

use App\Repositories\Common\CommonRepository;

interface RiderRepository
{
    public function getPickupList($request, $per_page);
    public function getDeliveryLists($request, $per_page);
    public function storeTrackingDetails($request);
    public function getDeliveryDashboard($inputs);
    public function getPickUpDashboard($inputs);


}