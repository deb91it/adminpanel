<?php namespace App\Repositories\Api\Store;

use Illuminate\Http\Request;
use App\DB\Api\Store;
use DB;

class EloquentStoreRepository implements StoreRepository
{
    protected $merchant_id;

    function __construct(Request $request)
    {
        $this->merchant_id = getMerchantId($request->header('Authorization'));
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getStoreList($per_page = 20) {

        $rows =  DB::table('stores as s')
            ->select('s.*', 'cz.zone_name')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 's.zone_id')
            ->orderBy('s.id', 'desc')
            ->where([ 's.merchant_id' => $this->merchant_id ])
            ->paginate($per_page);

        if(!empty($rows)) {
            foreach ($rows as $key => $row) {
                $row->categories = $this->getCategoriesByStore($row->id);
            }
        }

        return $rows;
    }

    public function findStore($id) {
        $row = DB::table('stores as s')
            ->select('s.*', 'cz.zone_name')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 's.zone_id')
            ->orderBy('s.id', 'desc')
            ->where([ 's.id' => $id, 's.merchant_id' => $this->merchant_id ])
            ->first();

        if(!empty($row)) {
            $row->categories = $this->getCategoriesByStore($row->id);
        }
        return $row;
    }

    private function getCategoriesByStore($store_id) {
        return DB::table('categories')
        ->select('id', 'name')
        ->whereRaw("id IN (SELECT categories_id FROM store_category WHERE store_id = {$store_id})")
        ->get();
    }

    public function postAddStore($input, $id = null) {
        if($id > 0) {
            $store = Store::find($id);
        } else {
            $store = new Store();
        }
        $store->merchant_id        = $this->merchant_id;
        $store->zone_id            = $input['zone_id'];
        $store->name               = $input['name'];
        $store->contact_name       = $input['contact_name'];
        $store->contact_phone      = $input['contact_phone'];
        $store->address            = $input['address'];
        $store->latitude           = $input['latitude'];
        $store->longitude          = $input['longitude'];
        $store->is_mart_ready      = $input['is_mart_ready'];
        $store->created_at         = date('Y-m-d H:i:s');
        if ($store->save()) {
            $this->storeProduct($input['categories'], $store->id, $id);
            return $store->id;
        }
        return 0;
    }

    private function storeProduct ($categories, $store_id, $id) {
        if($id > 0) {
           // Delete existing
            DB::table('store_category')->where('store_id', $store_id)->delete();
        }
        $categories = explode(',', $categories);
        if (!empty($categories)) {
            foreach ($categories as $k => $cat_id) {
                DB::table('store_category')->insert(
                    [
                        'store_id'       => $store_id,
                        'categories_id'  => $cat_id,
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s')
                    ]
                );
            }
        }
        return true;
    }

}
