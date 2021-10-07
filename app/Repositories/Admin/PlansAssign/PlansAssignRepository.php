<?php namespace App\Repositories\Admin\PlansAssign;

interface PlansAssignRepository
{
    public function getReportPaginated($request);
    public function store($input);
    public function findOrThrowException($id, $type);
    public function getPlannedByType($type);
    public function getUserDetails($member_id);
    public function details($member_id, $passenger_id);
    public function exportFile($request);
    public function getVehicleByMemberId($request, $id);
    public function getDriverByMemberId($request, $id);
    public function getAgentList();

}
