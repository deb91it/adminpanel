<?php namespace App\Repositories\Api\Product;

use Illuminate\Http\Request;
use App\DB\Api\Product;
use DB;

class EloquentProductRepository implements ProductRepository
{
    protected $merchant_id;

    function __construct(Request $request)
    {
        $this->merchant_id = getMerchantId($request->header('Authorization'));
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getProductList($request){
        $rows =  DB::table('products as p')
            ->select('p.*','s.name as store_name', 'c.name as category_name')
            ->join('stores as s', 's.id', '=', 'p.store_id')
            ->join('categories as c', 'c.id', '=', 'p.category_id')
            ->orderBy('p.id', 'desc')
            ->where([ 'p.merchant_id' => $this->merchant_id ]);
        if($request->get('returnType') == 'list'){
            $rows = $rows->where([ 'p.store_id' => $request->get('store_id') ])->get();
        }else {
            $rows = $rows->paginate($request->get('per_page') == '' ? 50 : $request->get('per_page'));
        }
        return $rows;
    }

    public function getProductListByStoreId($request)
    {
        // TODO: Implement getProductListByStoreId() method.
        $rows =  DB::table('products as p')
            ->select('p.*','s.name as store_name', 'c.name as category_name')
            ->join('stores as s', 's.id', '=', 'p.store_id')
            ->join('categories as c', 'c.id', '=', 'p.category_id')
            ->orderBy('p.id', 'desc')
            ->where([ 'p.merchant_id' => $this->merchant_id ])
            ->where([ 'p.store_id' => $request->get('store_id') ])
            ->get();

        return $rows;
    }

    public function findProduct($id){
        $rows =  DB::table('products as p')
            ->select('p.*', 's.name as store_name', 'c.name as category_name')
            ->join('stores as s', 's.id', '=', 'p.store_id')
            ->join('categories as c', 'c.id', '=', 'p.category_id')
            ->where([ 'p.id' => $id, 'p.merchant_id' => $this->merchant_id ])
            ->first();
        return $rows;
    }

    public function postStoreProduct($input, $id = null)
    {
        if($id > 0) {
            $product = Product::find($id);
        } else {
            $product = new Product();
        }
        $product->merchant_id        = $this->merchant_id;
        $product->name               = $input['name'];
        $product->subtitle           = $input['subtitle'];
        $product->sku                = isset($input['sku']) ? $input['sku'] : '';
        $product->store_id           = $input['store_id'];
        $product->category_id        = $input['category_id'];
        $product->description        = isset($input['description']) ? $input['description'] : '';
        $product->price              = $input['price'];
        $product->sell_price         = $input['sell_price'];
        $product->weight             = isset($input['weight']) ? $input['weight'] : 0;
        $product->width              = isset($input['width']) ? $input['width'] : 0;
        $product->height             = isset($input['height']) ? $input['height'] : 0;
        $product->depth              = isset($input['depth']) ? $input['depth'] : 0;
        $product->created_at         = date('Y-m-d H:i:s');
        if ($product->save()) {
            return $product->id;
        }
        return 0;
    }
}
