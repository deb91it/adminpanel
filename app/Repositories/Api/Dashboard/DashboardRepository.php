<?php namespace App\Repositories\Api\Dashboard;

interface DashboardRepository
{
    public function getCountedData($request);
    public function getGraphData($request);
}
