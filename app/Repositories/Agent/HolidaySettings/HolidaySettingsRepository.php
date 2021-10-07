<?php namespace App\Repositories\Agent\HolidaySettings;

interface HolidaySettingsRepository
{
    //public function getReportPaginated($request, $agent_id);
    public function getReportPaginated($request, $company_id);
    public function store($request);
    public function delete($id);
    public function findOrThrowException($id);
    public function update($request, $id);
}
