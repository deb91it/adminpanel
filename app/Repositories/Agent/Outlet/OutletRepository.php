<?php namespace App\Repositories\Agent\Outlet;

interface OutletRepository
{
    public function getReportPaginated($request, $company_id);
}
