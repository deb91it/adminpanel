<?php namespace App\Repositories\Agent\Dashboard;

interface DashboardRepository
{
    public function getDashboardData();
    public function getSrDailyData($from, $to);
    public function getTseDailyData($from, $to);
    public function getTotalCountedValue();
    public function getTodayCountedValue($from, $to);
    public function getBarGraphData($from, $to);
    public function getDashBoardRemarks($from, $to);
    public function getDashboardBarGraphData($request);
}
