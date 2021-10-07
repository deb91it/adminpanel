<?php namespace App\Repositories\Admin\AgentAccounts;

interface AgentAccountsRepository
{
    public function getReportPaginated($request);
    public function getReportDetails($request, $week_id);
    public function agentTransactionLogs($request, $agent_id);
}