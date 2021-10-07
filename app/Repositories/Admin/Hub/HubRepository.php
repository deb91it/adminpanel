<?php namespace App\Repositories\Admin\Hub;

interface HubRepository
{
    public function getReportPaginated($request);
    public function store($request);
    public function update($request, $id);



}
