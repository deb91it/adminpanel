<?php namespace App\Repositories\Api\Product;

interface ProductRepository
{
    public function getProductList($request);
    public function getProductListByStoreId($request);
    public function findProduct($id);
    public function postStoreProduct($inputs, $id = null);
}
