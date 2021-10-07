<?php namespace App\Repositories\Admin\Delivery;

interface DeliveryRepository
{
    public function getReportPaginated($request);
    public function getUserDetails($member_id);
    public function details($member_id, $passenger_id);
    public function exportFile($request);
    public function getVehicleByMemberId($request, $id);
    public function getDriverByMemberId($request, $id);
    public function getProducts($request);
    public function getAllProductByMerchantId($id);
    public function getAllStoreByMerchantId($id);
    public function findOrThrowException($id);
    public function store($request);
    public function getRiders($request);
    public function setApproval($request);
    public function storeRiders($request);
    public function rollBackStatus($request);
    public function checkDuplicateEntry($request);
    public function checkTransitBeforeDelivered($request);
    public function getAllPlansByMerchantId($merchant_id, $type);
    public function getAllDeliveries($request);
    public function update($request, $id);
    public function destroy($id);
    public function allStatus();
    public function amendmentStatus();
    public function getStatus();
    public function getHubs();
    public function getZones();
    public function getUserList();
    public function checkUserByNumber($request);
    public function checkAmendmentDeliveryPaidStatus($request);



}
