<?php namespace App\Repositories\Admin\Dashboard;

interface DashboardRepository
{
    public function getDashboardStatistics();
    public function getOperationStatistics($request);
    public function getFinancialStatistics($request);
    public function getCollectionStatistics($request);
}
