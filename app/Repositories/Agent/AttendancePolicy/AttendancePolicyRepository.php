<?php namespace App\Repositories\Agent\AttendancePolicy;

interface AttendancePolicyRepository
{
    public function store($input, $agent_id,$attendance_policy_head);
    public function getReportPaginated($request, $per_page, $agent_id, $status = 1, $order_by = 'id', $sort = 'asc');

}
