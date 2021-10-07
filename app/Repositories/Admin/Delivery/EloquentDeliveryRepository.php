<?php namespace App\Repositories\Admin\Delivery;
use App\DB\Admin\Delivery;
use App\DB\Admin\DeliveryProducts;
use App\DB\Admin\Plans;
use App\DB\Admin\TrackingDetails;
use App\DB\Permission;
use App\Repositories\Admin\FCMNotification\FCMNotificationRepository;
use DB;
use PDF;
use Datatables;


class EloquentDeliveryRepository implements DeliveryRepository
{
    protected $delivery;
    protected $fcm_notification;

    function __construct(Delivery $delivery
        ,FCMNotificationRepository $fcm_notification
    )
    {
        $this->delivery = $delivery;
        $this->fcm_notification = $fcm_notification;
    }

    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getAll() method.
    }

    public function getById($id, $status = 1)
    {
        // TODO: Implement getById() method.
    }

    public function create($inputs)
    {
        // TODO: Implement create() method.
    }
    public function store($input)
    {
        $delivery = new Delivery();
        $delivery->consignment_id        = strtoupper(getUniqueConsignmentId(8));
	//dd(getUniqueConsignmentId(8));
	    $delivery->merchant_id           = $input['merchant'];
        $delivery->hub_id                = !empty($input['hub_id']) ? $input['hub_id'] : 0;
        $delivery->recipient_name        = $input['recipient_name'];
        $delivery->recipient_number      = $input['recipient_number'];
        $delivery->recipient_email       = isset($input['recipient_email']) ? $input['recipient_email'] : '';
        $delivery->recipient_zone_id     = isset($input['recipient_zone']) ? $input['recipient_zone'] : 0;
        $delivery->recipient_address     = $input['recipient_address'];
        $delivery->latitude              = $input['latitude'];
        $delivery->longitude             = $input['longitude'];
        $delivery->package_description   = isset($input['package_description']) ? $input['package_description'] : '';
        $delivery->amount_to_be_collected = isset($input['amount_to_be_collected']) ? $input['amount_to_be_collected'] : 0;
        $delivery->charge                = $this->getCharge($input['plan']);
        $delivery->store_id              = isset($input['store']) ? $input['store'] : 0;
        $delivery->plan_id               = isset($input['plan']) ? $input['plan'] : 0;
        $delivery->plan_returned_id               = isset($input['plan_returned_id']) ? $input['plan_returned_id'] : 0;
        $delivery->receipent_alternative_number         = isset($input['receipent_alternative_number']) ? $input['receipent_alternative_number'] : '';
        //$delivery->delivery_note         = isset($input['delivery_note']) ? $input['delivery_note'] : '';
        $delivery->special_instruction   = isset($input['special_instruction']) ? $input['special_instruction'] : '';
        $delivery->merchant_order_id     = isset($input['merchant_order_id']) ? $input['merchant_order_id'] : '';
        $delivery->delivery_date         = isset($input['delivery_date']) && !empty($input['delivery_date'])? $input['delivery_date'] : null;
        $delivery->created_at            = date('Y-m-d H:i:s');
        $delivery->created_by            = get_logged_user_id();
        if ($delivery->save()) {
            $track['deliveries_id'] = $delivery->id;
            $track['flag_status_id'] = 2;
            $track['assign_to'] = 1;
            $track['is_hub'] = 1;
            $track['notes'] = $input['notes'];
            $track['description'] = 'Your order is being sorted.';
            $track['in_out'] = 1;
            $track['hub_id'] = $delivery->hub_id;
            storeTrackingData($track);
            if (isset($input['products'])) {
                $this->deliveryProduct($input['products'], $delivery->id, $id = 0);
            }
            $find_rider = $this->findRiderByZone($input['recipient_zone']);

            if (!empty($find_rider)) {
                $inputs = [
                    "tokens" => get_device_id_by_member_id($find_rider->member_id),
                    "title" => "Rider Pickup Notification",
                    "type" => "pickup",
                    "item_id" => $delivery->id,
                    "contents" => "'".$delivery->consignment_id."' is near to you. Please pick up the parcel as soon as possible.",
                    "icon" => null,
                    "click_icon" => null,
                ];
//                if (!empty($inputs['tokens'])) {
//                    $this->fcm_notification->sendFCMNotification($inputs);
//                }
            }
            return $delivery->id;
        }
        return 0;
    }
    private function findRiderByZone($zone_id)
    {
        return DB::table("riders")->select("member_id")->where("zone_id", $zone_id)->first();
    }
    private function getCharge($plan_id) {
        if ($plan_id) {
            $plan = Plans::find($plan_id);
            if (empty($plan))
            {
                return 0;
            }
            return $plan->charge;
        }
        return 0;
    }
    private function deliveryProduct ($products, $delivery_id, $id) {
        if($id > 0) {
            // Delete existing
            DB::table('delivery_product')->where('delivery_id', $delivery_id)->delete();
        }
        if (!empty($products)) {
            foreach ($products as $k => $cat_id) {
                DB::table('delivery_product')->insert(
                    [
                        'delivery_id'    => $delivery_id,
                        'product_id'     => $cat_id,
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s')
                    ]
                );
            }
        }
        return true;
    }

    public function findOrThrowException($id)
    {
        $row = DB::table('deliveries as del')
            ->select('del.*', 'dp.product_id')
            ->where('del.id', $id)
            ->leftjoin('delivery_product as dp', 'dp.delivery_id', '=', 'del.id' )
            ->first();

        if(!empty($row)) {
            $row->products = $this->getProductsByDelivery($row->id);
        }
        return $row;
    }

    public function getProductsByDelivery($delivery_id){
        return DB::table('products')
            ->select('id', 'name')
            ->whereRaw("id IN (SELECT product_id FROM delivery_product WHERE delivery_id = {$delivery_id})")
            ->get();
    }

    public function update($input, $id){
        $delivery = Delivery::where('id', $id)->first();
        //$delivery->consignment_id        = strtoupper(getUniqueConsignmentId(8));
        $delivery->merchant_id           = $input['merchant'];
        $delivery->hub_id                = !empty($input['hub_id']) ? $input['hub_id'] : 0;
        $delivery->recipient_name        = $input['recipient_name'];
        $delivery->recipient_number      = $input['recipient_number'];
        $delivery->recipient_email       = isset($input['recipient_email']) ? $input['recipient_email'] : '';
        $delivery->recipient_zone_id     = isset($input['recipient_zone']) ? $input['recipient_zone'] : 0;
        $delivery->recipient_address     = $input['recipient_address'];
        $delivery->latitude              = $input['latitude'];
        $delivery->longitude             = $input['longitude'];
        $delivery->package_description   = isset($input['package_description']) ? $input['package_description'] : '';
        $delivery->amount_to_be_collected = isset($input['amount_to_be_collected']) ? $input['amount_to_be_collected'] : 0;
        $delivery->charge                = $this->getCharge($input['plan']);
        $delivery->store_id              = isset($input['store']) ? $input['store'] : 0;
        $delivery->plan_id               = isset($input['plan']) ? $input['plan'] : 0;
        $delivery->plan_returned_id      = isset($input['plan_returned_id']) ? $input['plan_returned_id'] : 0;
        $delivery->receipent_alternative_number         = isset($input['receipent_alternative_number']) ? $input['receipent_alternative_number'] : '';
        $delivery->special_instruction   = isset($input['special_instruction']) ? $input['special_instruction'] : '';
        $delivery->merchant_order_id     = isset($input['merchant_order_id']) ? $input['merchant_order_id'] : '';
        $delivery->delivery_date         = isset($input['delivery_date']) && !empty($input['delivery_date'])? $input['delivery_date'] : null;
        $delivery->updated_at            = date('Y-m-d H:i:s');
        $delivery->updated_by            = get_logged_user_id();
        if ($delivery->save()) {
            /*if (isset($input['products'])) {
                $this->deliveryProduct($input['products'], $id);
            }*/

            if (isset($input['products'])) {
                $this->deliveryProduct($input['products'], $delivery->id, $id);
            }
            return $delivery->id;

            /*return $id;*/
        }
        return 0;
    }

    public function delete($id)
    {
        DB::table('delivery')
            ->where('id', $id)
            ->update([
                'status' => 0,
            ]);
        return true;
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
        DB::table('deliveries')
            ->where('id', $id)
            ->delete();
        return 1;
    }

    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }

    public function getUserDetails($member_id)
    {
        return $this->Delivery->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $delivery_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getReportPaginated($request){
        $from = $request->delivery_date;
        $to = $request->delivery_date;
        $date_range = $request->get('columns')[13]['search']['value'];
        $status = $request->get('columns')[8]['search']['value'];
        $hub_id = $request->get('columns')[10]['search']['value'];
        $consignment_ids = $request->get('columns')[0]['search']['value'];
        $entryBy = $request->get('columns')[18]['search']['value'];
        $rider = $request->get('columns')[15]['search']['value'];

        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (date_validate($start_date)  || date_validate($end_date)) {
                $from = $start_date;
                $to = $end_date;
            }
        }

        if (isset($request->query_string) && !empty($request->query_string))
        {
            if (!empty($request->query_string))
            {
                list($from, $to) = explode("~", $request->query_string);
            }
            if ($request->status > 0)
            {
                $status = $request->status;
            }
        }

        $query = DB::table('deliveries as del')
            ->select('del.*','cz.zone_name','s.name','p.plan_name','rp.plan_name as returned_plan_name','del.id as deli_id','merch.business_name','mem.mobile_no as merchant_mobile_no','fs.flag_text','fs.color_code',
                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS full_name'),
                DB::raw('CONCAT(au.first_name, " ", au.last_name) AS entry_created_by'),
                DB::raw('CONCAT(uau.first_name, " ", uau.last_name) AS entry_updated_by'),
                DB::raw('(COALESCE(SUM(del.amount_to_be_collected), 0)) AS total_collected_amount'),
                DB::raw('(COALESCE(SUM(del.receive_amount), 0)) AS total_receive_amount')
            )
            ->leftjoin('courier_zones as cz','cz.id', '=', 'del.recipient_zone_id')
            ->leftjoin('stores as s','s.id', '=', 'del.store_id')
            ->leftjoin('plans as p','p.id', '=', 'del.plan_id')
            ->leftjoin('plans as rp','rp.id', '=', 'del.plan_returned_id')
            ->leftjoin('merchants as merch','merch.id', '=', 'del.merchant_id')
            ->leftjoin('members as mem','mem.id', '=', 'merch.member_id')
            ->leftjoin('admin_users as au','au.member_id', '=', 'del.created_by')
            ->leftjoin('admin_users as uau','uau.member_id', '=', 'del.updated_by')
            ->join('tracking_details_summary as tds','tds.deliveries_id', '=', 'del.id')
            ->join('flag_status as fs','fs.id', '=', 'del.status')
            ->groupBy('del.id')
            ->orderBy('del.id','desc');

            if (get_admin_hub_id() > 0) {
                $query = $query->where(['tds.hub_id' => get_admin_hub_id()]);
            }
            if (hasRoleToThisUser(get_logged_user_id()) == 5) {
                $query = $query->where(["del.created_by" => get_logged_user_id()])
                    ->whereIn("del.status", [1,2,3] );
            }
            if ($request->has('merchant_id') && !empty($request->get('merchant_id')))
            {
                $from = "2018-01-01 00:00:01";
                $to = date('Y-m-d')." 23:59:59";
                $query = $query->where("del.merchant_id", $request->get('merchant_id'));
            }

            if (!empty($consignment_ids) && empty($date_range))
            {
                $consignment_ids = explode(",", $consignment_ids);
                $query = $query->whereIn("del.consignment_id", $consignment_ids);
            }else{
                $query = $query->whereBetween('del.created_at',[$from." 00:00:01",$to." 23:59:59"]);
            }

            if (!empty($status))
            {
                $query = $query->where(['del.status' => $status]);
            }

            if (!empty($entryBy))
            {
                $query = $query->where(['del.created_by' => $entryBy]);
            }

            if (!empty($rider))
            {
//                dd($rider);
                $query = $query->join('tracking_details as td', 'del.id', '=', 'td.deliveries_id')
                ->where('td.flag_status_id','=', 10)
                ->where('td.is_hub','=', 0)
                ->where('td.is_active','=', 1)
                ->where('td.assign_to','=',$rider);
            }

            if (!empty($hub_id))
            {
                if (get_admin_hub_id() > 0) {
                    $query = $query->where("del.recipient_zone_id", $hub_id);
                }else{
                    $query = $query->where("del.hub_id", $hub_id);
                }
            }

            //For amendment delivery section
            if (isset($request->page_status) && !empty($request->page_status == 'amendment') && empty($status))
            {
                $query = $query->whereIn('del.status', [ 6,7,8,12,16]);
            }

            //Find The total value
            $total = $this->findTotalAmount($request);



        return Datatables::of($query, $total)
            ->filterColumn('consignment_id', function($query, $keyword) {
                $query->whereRaw("consignment_id like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('merchant_order_id', function($query, $keyword) {
                $query->whereRaw("del.merchant_order_id like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('recipient_name', function($query, $keyword) {
                $query->whereRaw("del.recipient_number like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('full_name', function($query, $keyword) {
                $query->whereRaw("merch.business_name like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="#" onclick="viewProducts('.$user->deli_id.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Products"><i class="fa fa-cubes"></i></a>
                    <a href="'.route('admin.delivery.edit',array($user->deli_id)).'"  class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit Products"><i class="fa fa-edit"></i></a>
                    <span onclick="deleteDelivery('.$user->deli_id.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete Delivery"><i class="fa fa-trash"></i></span>
                    ';
            })
            ->with('totalCollected', round(empty($total) ? 0 :(int) $total->total_collected_amount))
            ->with('totalReceived', round(empty($total) ? 0 : (int) $total->total_receive_amount))
            ->make(true);
    }

    private function findTotalAmount($request)
    {
        $from = $request->delivery_date;
        $to = $request->delivery_date;
        $date_range = $request->get('columns')[13]['search']['value'];
        $status = $request->get('columns')[8]['search']['value'];
        $hub_id = $request->get('columns')[10]['search']['value'];
        $consignment_ids = $request->get('columns')[0]['search']['value'];
        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (date_validate($start_date)  || date_validate($end_date)) {
                $from = $start_date;
                $to = $end_date;
            }
        }

        if (isset($request->query_string) && !empty($request->query_string))
        {
            if (!empty($request->query_string))
            {
                list($from, $to) = explode("~", $request->query_string);
            }
            if ($request->status > 0)
            {
                $status = $request->status;
            }
        }

        $query = DB::table('deliveries as del')
            ->select(
                DB::raw('(COALESCE(SUM(del.amount_to_be_collected), 0)) AS total_collected_amount'),
                DB::raw('(COALESCE(SUM(del.receive_amount), 0)) AS total_receive_amount')
            )
            ->leftjoin('courier_zones as cz','cz.id', '=', 'del.recipient_zone_id')
            ->leftjoin('stores as s','s.id', '=', 'del.store_id')
            ->leftjoin('plans as p','p.id', '=', 'del.plan_id')
            ->leftjoin('merchants as merch','merch.id', '=', 'del.merchant_id')
            ->join('tracking_details_summary as tds','tds.deliveries_id', '=', 'del.id')
            ->join('flag_status as fs','fs.id', '=', 'del.status')
//            ->groupBy('del.id')
            ->orderBy('del.id','desc');
        if (get_admin_hub_id() > 0) {
            $query = $query->where(['tds.hub_id' => get_admin_hub_id()]);
        }

        if (!empty($consignment_ids) && empty($date_range))
        {
            $consignment_ids = explode(",", $consignment_ids);
            $query = $query->whereIn("del.consignment_id", $consignment_ids);
        }else{
            $query = $query->whereBetween('del.created_at',[$from." 00:00:01",$to." 23:59:59"]);
        }

        if (!empty($status))
        {
            $query = $query->where(['del.status' => $status]);
        }
        if (!empty($hub_id))
        {
            if (get_admin_hub_id() > 0) {
                $query = $query->where("del.recipient_zone_id", $hub_id);
            }else{
                $query = $query->where("del.hub_id", $hub_id);
            }
        }

        //For amendment delivery section
        if (isset($request->page_status) && !empty($request->page_status == 'amendment') && empty($status))
        {
            $query = $query->whereIn('del.status', [ 6,7,8 ]);
        }
        $query = $query->first();
        return $query;
    }

    public function exportFile($request)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $query_string = $request['query_string'];
        $status = $request['status'];
        $entryBy = $request['entry_by'];

        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $query = DB::table('deliveries as del')
            ->select(
                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS OwnerName'),"merch.business_name as BusinessName",
                "merch.merchant_code as MerchantID","del.recipient_name as RecipientName","del.recipient_number as RecipientContact",
                "del.recipient_email as RecipientEmail","del.recipient_address as RecipientAddress","fs.flag_text as Status",
                "cz.zone_name as RecipientZone",
                "p.plan_name as DeliveredPlan","rp.plan_name as ReturnedPlan","del.merchant_order_id as MerchantOrderID",
                "del.amount_to_be_collected as AmountToBeCollected","h.hub_name as TransitHUB"
            )
            ->leftjoin('courier_zones as cz','cz.id', '=', 'del.recipient_zone_id')
            ->leftjoin('stores as s','s.id', '=', 'del.store_id')
            ->leftjoin('plans as p','p.id', '=', 'del.plan_id')
            ->leftjoin('plans as rp','rp.id', '=', 'del.plan_returned_id')
            ->leftjoin('merchants as merch','merch.id', '=', 'del.merchant_id')
            ->leftjoin('members as mem','mem.id', '=', 'merch.member_id')
            ->leftjoin('admin_users as au','au.member_id', '=', 'del.created_by')
            ->leftjoin('admin_users as uau','uau.member_id', '=', 'del.updated_by')
            ->leftjoin('hub as h','h.id', '=', 'del.hub_id')
            ->join('tracking_details_summary as tds','tds.deliveries_id', '=', 'del.id')
            ->join('flag_status as fs','fs.id', '=', 'del.status')
            ->groupBy('del.id')
            ->orderBy('del.id','desc');
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('del.created_at',[$start_date." 00:00:01",$end_date." 23:59:59"]);
        }


        if (!empty($status))
        {
            $query = $query->where(['del.status' => $status]);
        }

        if (!empty($entryBy))
        {
            $query = $query->where(['del.created_by' => $entryBy]);
        }
        if (!empty($query_string))
        {
            $query = $query->where(function ($query)  use ($query_string){
                $query->where('del.consignment_id', 'like', "%{$query_string}%")
                    ->orWhere('del.merchant_order_id', 'like', "%{$query_string}%")
                    ->orWhere('del.recipient_number', 'like', "%{$query_string}%")
                    ->orWhere('merch.business_name', 'like', "%{$query_string}%")
                ;
            });
        }
        $data = $query->get();
        return $data;
    }

    public function getVehicleByMemberId($request, $id)
    {
        return  DB::table('Delivery as merch')
            ->select('v.model', 'v.license_plate')
            ->join('vehicles as v', 'v.Plan_id', '=', 'merch.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getDriverByMemberId($request, $id)
    {
        return  DB::table('Delivery as merch')
            ->select('d.first_name as driver_first_name', 'd.last_name as driver_last_name', 'dc.is_verified as license_vefication_status')
            ->join('drivers as d', 'd.Plan_id', '=', 'merch.id')
            ->leftJoin('driving_license as dc', 'dc.driver_id', '=', 'd.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getPlanList()
    {
        return ['' => 'Select Plan']
            + DB::table('Delivery as a')
                ->select(DB::raw('CONCAT(a.first_name, " ", a.last_name, " - ", b.mobile_no ) AS Plan'), 'a.id')
                ->join('members as b', 'b.id', '=', 'a.member_id')
                ->orderBy('a.id', 'asc')
                // ->where(['b.is_active' => 1])
                ->lists('Plan', 'id');
    }

    public function getProducts($input)
    {
        return DB::table('delivery_product as dp')
            ->select('p.*','c.name as cat_name','s.name as store_name','dp.delivery_id','dp.is_approve')
            ->join('products as p', 'dp.product_id', '=', 'p.id')
            ->leftjoin('deliveries as d', 'dp.delivery_id', '=', 'd.id')
            ->leftjoin('categories as c', 'p.category_id', '=', 'c.id')
            ->leftjoin('stores as s', 'p.store_id', '=', 's.id')
            ->groupBy('dp.delivery_id','dp.product_id')
            ->where(['dp.delivery_id'=>$input['delivery_id']])
            ->get();
    }

    public function getAllProductByMerchantId($id){
        return  DB::table('products')
            ->select('id', 'name')
            ->where('merchant_id', $id)
            ->get();
    }
    public function getAllStoreByMerchantId($id){
        return  DB::table('stores')
            ->select('id', 'name')
            ->where('merchant_id', $id)
            ->get();
    }
    public function getAllPlansByMerchantId($id, $type){
        return DB::table('plan_assign_to_merchant as pa')
            ->select($this->getSelectItemDuringEdit())
            ->join('merchants as merch', 'merch.id', '=', 'pa.merchant_id')
            ->join('plans as p', 'p.id', '=', 'pa.plan_id')
            ->where('pa.merchant_id', $id)
            ->where('p.plan_type', $type)
            ->where('pa.status', 1)
            ->get();
    }

    protected function getSelectItemDuringEdit()
    {
        return [
            'pa.*','p.plan_code','p.plan_name','merch.id as merchant_id',
            DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS full_name'),
        ];
    }


    public function setApproval($input)
    {
        if (!empty($input['is_approve'])){
            foreach ($input['is_approve'] as $val){
                $changeStatus = DeliveryProducts::where(['delivery_id'=>$input['delivery_id'],'product_id'=>$val])->first();
                $changeStatus->is_approve = 2;
                $changeStatus->save();
            }
            return 1;
        }
        return 0;
    }

    public function getRiders($input)
    {
        if ($input->is_hub == 2){
            return  DB::table('riders as r')
                ->select('p.zone_name as main_name','r.id',DB::raw('CONCAT(r.first_name, " ", r.last_name) AS name'))
                ->join('courier_zones as p', 'p.id', '=', 'r.zone_id')
                //->join("courier_zones as cz", "cz.id", "=", "r.zone_id")
                ->get();
        }
        if ($input->is_hub == 1){
            return  DB::table('hub as h')
                ->select('h.id','h.hub_name AS name',DB::raw('CONCAT(a.first_name, " ", a.last_name) AS main_name'))
                ->leftjoin('admin_users as a', 'h.id', '=', 'a.hub_id')
                ->where('h.status', 1)
                ->groupBy('h.id')
                ->get();
        }

    }

    public function trackingDetails($id)
    {
        return DB::table('tracking_details as td')
            ->select('td.*',DB::raw('CONCAT(au.first_name, " ", au.last_name) AS full_name'))
            ->leftjoin('flag_status as fg','fg.id','=','td.flag_status_id')
            ->leftjoin('members as m','m.id','=','td.entry_by')
            ->leftjoin('admin_users as au','m.id','=','au.member_id')
            ->where(['td.deliveries_id' => $id, 'td.is_active' => 1])
            ->get();
    }

    public function getStatus()
    {
        if (hasRoleToThisUser(get_logged_user_id()) > 1 && hasRoleToThisUser(get_logged_user_id()) == 5)
        {
            return  DB::table('flag_status')
                ->select('id','flag_text')
                ->whereIn('id',[1,2,3,5])
                ->get();
        }elseif (hasRoleToThisUser(get_logged_user_id()) > 1) {
            return  DB::table('flag_status')
                ->select('id','flag_text')
                ->whereNotIn('id',[4,11])
                ->get();
        }else{
            return  DB::table('flag_status')
                ->select('id','flag_text')
                ->whereNotIn('id',[4])
                ->get();
        }
    }

    public function allStatus()
    {
        return  DB::table('flag_status')
            ->select('id','flag_text')
            ->get();
    }

    public function amendmentStatus()
    {
        return  DB::table('flag_status')
            ->select('id','flag_text')
            ->whereIn('id', [6,7,8])
            ->get();
    }

    public function storeRiders($input)
    {

        $track = [];
        $desc = '';
        switch ($input['flag_status_id']){
            case 1: $desc = 'Your order has been successfully placed.';
            break;

            case 2: $desc = 'Your order has been accepted.';
            break;

            case 3: $desc = 'Your order is being sorted.';
            break;

            case 4: $desc = 'Your order has left the sorting facility.';
            break;

            case 5: $desc = 'Your order is in transit.';
            break;

            case 6: $desc = 'Your order has been delivered.';
            break;

            case 7: $desc = 'Your order has been RETURNED FROM HUB.';
            break;

            case 8: $desc = 'Your order has been marked for return.';
            break;

            case 9: $desc = 'Your order is being held at sorting.';
            break;

            case 12: $desc = 'Your order has been partially delivered.';
            break;

            case 13: $desc = 'Consignment cash has been collected successfully';
            break;

            case 14: $desc = 'Cash has been ready to hand over.';
            break;

            case 15: $desc = 'The cash amount has been successfully hand over.';
            break;

            case 16: $desc = 'Your order has been exchanged.';
                break;

            case 17: $desc = 'Returned cash has been collected successfully';
                break;

            case 18: $desc = 'Returned Cash has been ready to hand over.';
                break;

            case 19: $desc = 'The returned amount has been successfully hand over.';
                break;

            case 20: $desc = 'Your consignment has been rescheduled.';
                break;


        }

        if ($input['flag_status_id'] == 10 || $input['flag_status_id'] == 21)
        {
            $msg = $input['flag_status_id'] == 21 ? "Your order will be pick up by " : 'Your order is assigned to ';
            $assign =   DB::table('riders as r')
                ->select(DB::raw('CONCAT(r.first_name, " ", r.last_name) AS name'),'r.id', 'm.mobile_no')
                ->join('members as m','m.id','=','r.member_id')
                ->where('r.id',$input['assign_to'])
                ->first();
            $desc = !empty($assign) ? $msg.$assign->name." (Contact: ". $assign->mobile_no.")": '';
        }

        if ( get_admin_hub_id() == 0 )
        {
            if ($input['is_hub'] == 1 && $input['flag_status_id'] == 5){
                $assign =   DB::table('hub')
                    ->select('hub_name AS name','id')
                    ->where('id',$input['assign_to'])
                    ->first();
                $desc = !empty($assign) ? "Your order is in transit ({$assign->name}).": '';
            }
        }
        $assign_to = !empty($input['assign_to']) ? $input['assign_to'] : 0;
        $is_hub = !empty($input['is_hub']) ? $input['is_hub'] : 0;
        $in_out = $input['is_hub'] == 1 ? 2 : 1;
        $flag_status_id = $input['is_hub'] == 1 ? 5 : $input['flag_status_id'];

        foreach ($input['deliveried_id'] as $val)
        {
            $hub = DB::table('tracking_details_summary')->select('hub_id')->where('deliveries_id',$val)->first();
            $track['deliveries_id'] = $val;
            $track['flag_status_id'] = $flag_status_id;
            $track['assign_to'] = $assign_to;
            $track['is_hub'] = $is_hub;
            $track['notes'] = isset($input['notes']) && !empty($input['notes']) ? $input['notes'] : $input['note'.$val];
            $track['receive_amount'] = isset($input['receive_amount']) && !empty($input['receive_amount']) ? $input['receive_amount'] : $input['received_amount'.$val];
            $track['description'] = $desc;
            $track['in_out'] = $in_out;
            $track['hub_id'] = $input['is_hub'] == 1 ? $input['assign_to'] : $hub->hub_id;
            $storeTrack = storeTrackingData($track);

            if ($input['flag_status_id'] == 10 || $input['flag_status_id'] == 21) {
                $deliveries = Delivery::find($val);
                if (!empty($deliveries)) {
                    $FCMMsg = "'".$deliveries->consignment_id."' is assign to you. Please deliver the parcel as soon as possible.";
                    $title = "Provati Rider - Delivery Notification";
                    $type = "delivery";
                    if ($input['flag_status_id'] == 21)
                    {
                        $FCMMsg = "'".$deliveries->consignment_id."' is assign to you. Please picked up the parcel as soon as possible.";
                        $title = "Provati Rider - Pick up Notification";
                        $type = "pickup";
                    }
                    $inputs = [
                        "tokens" => get_device_id_by_member_id(get_member_id_by_rider_id($input['assign_to'])),
                        "title" => $title,
                        "type" => $type,
                        "item_id" => $deliveries->id,
                        "consignment_id" => $deliveries->consignment_id,
                        "contents" => $FCMMsg,
                        "icon" => null,
                        "click_icon" => null,
                    ];
                    if (!empty($inputs['tokens'])) {
                        $this->fcm_notification->sendFCMNotification($inputs);
                    }
                }
            }
        }
        if($input['flag_status_id'] == 3){
            return $input['deliveried_id'];
        }
        return 1;
    }

    public function rollBackStatus($input)
    {
        // TODO: Implement rollBackStatus() method.
        DB::beginTransaction();
        try {
            foreach ($input['deliveried_id'] as $val)
            {
                $check = TrackingDetails::select("id", "flag_status_id", "deliveries_id")
                    ->where(["deliveries_id" => $val , "is_active" => 1])
                    ->whereIn("flag_status_id", [6,7,8])
                    ->orderBy("id","DESC")
                    ->first();
                if (empty($check))
                {
                    return 0;
                }
                $deliveries = Delivery::find($val);
                $deliveries->invoice_date = null;
                $deliveries->receive_amount = 0;
                $deliveries->cod_charge = 0;
                if ($check->flag_status_id == 6) {
                    $deliveries->delivery_date = null;
                }
                elseif ($check->flag_status_id == 7 || $check->flag_status_id == 8) {
                    $deliveries->return_date = null;
                }else{
                    $deliveries->delivery_date = null;
                    $deliveries->return_date = null;
                }
                $deliveries->save();
                $track = TrackingDetails::find($check->id);
                $track->is_active = 0;
                $track->save();
            }
            DB::commit();
            // all good
        } catch (Exception $e) {
            DB::rollback();
            //return 0;
            return $e->getMessage();
        }

    }

    public function checkDuplicateEntry($request)
    {
        $consignment = [];
        foreach ($request->deliveried_id as $item) {
            if ($request['consignment_current_status_'.$item] == 6 || $request['consignment_current_status_'.$item] == 7 || $request['consignment_current_status_'.$item] == 8 )
            {
                $flagMessage = $this->showFlagMessage($request['consignment_current_status_'.$item]);
                $consignment[] = "Consignment ID '".$request['consignment_id_'.$item]."' ".$flagMessage;
            }
        }
        if (!empty($consignment))
        {
            return $consignment;
        }
        return 0;
    }

    public function checkAmendmentDeliveryPaidStatus($request)
    {
        // TODO: Implement checkAmendmentDeliveryPaidStatus() method.
        $consignment = [];
        foreach ($request->deliveried_id as $item) {
            $check = Delivery::where(['id' => $item, 'payment_status' => 1])->first();
            if (!empty($check))
            {
                $consignment[] = "CONSIGNMENT ID '".$check->consignment_id."' already marked as paid.";
            }
        }
        return $consignment;
    }

    public function checkTransitBeforeDelivered($request) {
        $consignment = [];
        foreach ($request->deliveried_id as $k => $item) {
//           print_r($request->deliveried_id);exit();
            $check = DB::table("tracking_details")
                ->where(["deliveries_id" => $item, "flag_status_id" => 10 ])
                ->orderBy("id", "DESC")
                ->first();
            //print_r($check);exit();
            if (empty($check))
            {
                $consignment[$k] = "Consignment ID '".$request['consignment_id_'.$item]."' need to assign a rider before change the status as delivered or returned.";
            }
        }
        return $consignment;
    }

    private function showFlagMessage($val)
    {
        switch ($val) {
            case "6":
                return "already marked as delivered.";
                break;
            case "7":
                return "already marked as returned.";
                break;
            case "8":
                return "already marked as returned from hub.";
                break;
            case "10":
                return "already assigned this item to the rider";
                break;
            default:
                return "already sorted.";
        }
    }

    public function getAllDeliveries($request)
    {
        //echo sort($request->delivery_id);
        $delv = explode(",",$request->delivery_id);
        $from = date("Y-m-d");
        $to = date("Y-m-d");
        $query = DB::table('deliveries as del')
            ->select('del.*','cz.zone_name','s.name','p.plan_name','del.id as deli_id','merch.business_name','fs.flag_text','fs.color_code',
                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS full_name')
            )
            ->leftjoin('courier_zones as cz','cz.id', '=', 'del.recipient_zone_id')
            ->leftjoin('stores as s','s.id', '=', 'del.store_id')
            ->leftjoin('plans as p','p.id', '=', 'del.plan_id')
            ->leftjoin('merchants as merch','merch.id', '=', 'del.merchant_id')
            ->join('tracking_details_summary as tds','tds.deliveries_id', '=', 'del.id')
            ->join('flag_status as fs','fs.id', '=', 'del.status')
//            ->whereBetween('del.delivery_date',[$from,$to])
            ->whereIn('del.id',$delv)
            ->groupBy('del.id')
            ->orderBy('del.id','desc')

            ->get();
        return $query;
    }

    public function getHubs()
    {
        return DB::table("hub")
            ->select("hub_name","id")
            ->where("status", 1)
            ->get();
    }

    public function getZones()
    {
        return DB::table("courier_zones")
            ->select("zone_name","id")
            ->where("status", 1)
            ->get();
    }

    public function getUserList()
    {
        return DB::table("admin_users")
            ->select("member_id as id",DB::raw('CONCAT(first_name, " ", last_name) AS full_name'))
            ->where("status", 1)
            ->whereNotIn("id",[1])
            ->get();
    }

    public function checkUserByNumber($request)
    {
        // TODO: Implement checkUserByNumber() method.
        return DB::table("deliveries")
            ->select("recipient_name","recipient_number", "recipient_email", "recipient_zone_id", "recipient_address", "latitude", "longitude")
            ->where(["recipient_number" => $request->recipient_number])
            ->first();
    }
}
