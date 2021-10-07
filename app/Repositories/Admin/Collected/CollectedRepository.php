<?php namespace App\Repositories\Admin\Collected;

interface CollectedRepository
{
    public function getReportPaginated($request);
}
