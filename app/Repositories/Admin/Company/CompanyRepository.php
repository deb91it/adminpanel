<?php namespace App\Repositories\Admin\Company;

interface CompanyRepository
{
    public function getReportPaginated($request);
    public function store($input);
    public function findOrThrowException($id);
    public function getUserDetails($member_id);
    public function details($member_id, $passenger_id);
    public function exportFile($request);
}
