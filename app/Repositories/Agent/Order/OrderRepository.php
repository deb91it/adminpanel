<?php namespace App\Repositories\Agent\Order;

interface OrderRepository
{
    public function saveAttendanceManually($input);
    public function exportFile($input);
    public function getReportPaginated($request, $per_page, $agent_id,$from,$to,$type, $status = 1);

}
