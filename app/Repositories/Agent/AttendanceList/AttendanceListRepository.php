<?php namespace App\Repositories\Agent\AttendanceList;

interface AttendanceListRepository
{
    public function saveAttendanceManually($input);
    public function exportFile($input);
    public function getReportPaginated($request, $per_page, $agent_id,$from,$to, $status = 1);

}
