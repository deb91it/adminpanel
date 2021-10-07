<?php namespace App\Repositories\Api\Employee;

interface EmployeeRepository
{
    public function getSrDashboardData($input, $sr_id);
    public function getSrMeetingData($request, $sr_id);
    public function getSrOutletOwnerList($input, $sr_id);
    public function getTseDashboardData($input, $tse_id);
    public function getTseChartData($request, $tse_id, $chart_type);
    public function getSrListOfTse($input, $tse_id);
    public function explodeArraylistInArray($input, $index);
}