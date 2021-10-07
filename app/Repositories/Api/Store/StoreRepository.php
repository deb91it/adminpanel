<?php namespace App\Repositories\Api\Store;

interface StoreRepository
{
    public function getStoreList($per_page = 20);
    public function findStore($id);
    public function postAddStore($inputs, $id = null);
}
