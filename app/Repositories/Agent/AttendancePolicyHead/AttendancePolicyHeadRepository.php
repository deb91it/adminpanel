<?php namespace App\Repositories\Agent\AttendancePolicyHead;

interface AttendancePolicyHeadRepository
{
    public function store($input, $agent_id);
    public function update($input);
    public function getReportPaginated($request, $per_page, $agent_id, $status = 1, $order_by = 'id', $sort = 'asc');

}
