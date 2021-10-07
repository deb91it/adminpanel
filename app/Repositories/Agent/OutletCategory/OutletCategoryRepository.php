<?php namespace App\Repositories\Agent\OutletCategory;

interface OutletCategoryRepository
{
    //public function getReportPaginated($request, $agent_id);
    public function getReportPaginated($request, $per_page, $agent_id, $status = 1);
    public function store($input, $agent_id);
    public function findOrThrowException($id, $agent_id);
    public function getUserDetails($member_id);
    public function details($member_id, $passenger_id);
    public function exportFile($request, $agent_id);
    public function updateProfile($input, $id);
    public function getCompanyRoleList($company_id);
    public function getCompanySalesPersonList($company_id);
}
