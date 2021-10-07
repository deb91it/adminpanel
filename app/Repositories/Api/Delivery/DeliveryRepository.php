<?php namespace App\Repositories\Api\Delivery;

interface DeliveryRepository
{
    public function getDeliveryList($request, $per_page = 20);
    public function findDelivery($id);
    public function getDeliveryTrackingLogs($id);
    public function postStoreDelivery($inputs, $id = null);
}
