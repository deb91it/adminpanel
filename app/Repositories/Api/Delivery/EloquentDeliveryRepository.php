<?php namespace App\Repositories\Api\Delivery;

use App\DB\Admin\TrackingDetails;
use Illuminate\Http\Request;
use App\DB\Api\Delivery;
use DB;
use App\Repositories\Admin\FCMNotification\FCMNotificationRepository;

class EloquentDeliveryRepository implements DeliveryRepository
{
    protected $merchant_id;
    protected $fcm_notification;

    function __construct(
        Request $request,
        FCMNotificationRepository $fcm_notification)
    {
        $this->merchant_id = getMerchantId($request->header('Authorization'));
        $this->fcm_notification = $fcm_notification;
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getDeliveryList($request, $per_page = 20) {
        $rows =  DB::table('deliveries as d')
            ->select(
                'd.*',
                'fs.flag_text as status_text', 'fs.color_code as status_color',
                's.name as store_name',
                'p.plan_name',
                'rp.plan_name as returned_plan_name',
                'cz.zone_name as recipient_zone_name'
            )
            ->join('flag_status as fs', 'fs.id', '=', 'd.status')
            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
            ->leftjoin('plans as rp','rp.id', '=', 'd.plan_returned_id')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
            ->where([ 'd.merchant_id' => $this->merchant_id ]);
        if ($request->get('type') == 'aging') {
            $rows = $rows->whereIn('d.status', [1,9]);
        }
        if (isset($request->flag) && !empty($request->flag)) {
            $rows = $rows->where("d.status", $request->flag);
        }
        if (isset($request->startDate) && !empty($request->startDate) || !empty($request->endDate))
        {
            $startDate = empty($request->startDate) ? date("Y-m-d 00:00:01") : $request->startDate;
            $endDate = empty($request->endDate) ? date("Y-m-d 23:59:59") : $request->endDate;
            $rows = $rows->whereBetween("d.created_at",[$startDate,$endDate]);
        }
        if (isset($request->query_string) && !empty($request->query_string))
        {
            $input = $request->query_string;
            //echo $input;exit();
            $rows = $rows->where(function($q) use($input) {
                $q->where('consignment_id', 'LIKE', '%' . $input . '%');
                $q->orWhere('recipient_number', 'LIKE', '%' . $input . '%');
                $q->orWhere('merchant_order_id', 'LIKE', '%' . $input . '%');
                $q->orWhere('recipient_name', 'LIKE', '%' . $input . '%');
            });

        }
        if (isset($request->payment_status) && !empty($request->payment_status)) {
            $request->payment_status = $request->payment_status == 'paid' ? 1 : 0;
            $rows = $rows->where("d.payment_status", $request->payment_status);
        }

        $rows = $rows->orderBy('d.id', 'desc')
            ->paginate($per_page);

        if(!empty($rows)) {
            foreach ($rows as $key => $row) {
                $row->payment_status = $row->payment_status === 0 ? 'Unpaid' : 'Paid';
                $row->products = $this->getProductsByDelivery($row->id);
            }
        }

        return $rows;
    }

    private function getProductsByDelivery($delivery_id) {
        return DB::table('products')
        ->select('id', 'name')
        ->whereRaw("id IN (SELECT product_id FROM delivery_product WHERE delivery_id = {$delivery_id})")
        ->get();
    }

    public function findDelivery($id) {
        $row =  DB::table('deliveries as d')
            ->select(
                'd.*',
                's.name as store_name',
                'p.plan_name',
                'cz.zone_name as recipient_zone_name'
            )
            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
            ->where([ 'd.id' => $id, 'd.merchant_id' => $this->merchant_id ])
            ->orderBy('d.id', 'desc')
            ->first();

        if(!empty($row)) {
            $row->products = $this->getProductsByDelivery($row->id);
        }
        return $row;
    }

    public function getDeliveryTrackingLogs($id){
        return TrackingDetails::select(
                'tracking_details.*','fs.flag_text','fs.color_code',DB::raw('CONCAT(au.first_name, " ", au.last_name) AS full_name')
            )
            ->join('flag_status as fs', 'fs.id', '=', 'tracking_details.flag_status_id')
            ->leftjoin('members as m','m.id','=','tracking_details.entry_by')
            ->leftjoin('admin_users as au','m.id','=','au.member_id')
            ->where([ 'tracking_details.deliveries_id' => $id ])
            ->whereNotIn('tracking_details.flag_status_id',[1])
            ->orderBy('tracking_details.id', 'asc')
            ->get();
    }

    public function postStoreDelivery($input, $id = null)
    {
        $hub_id = $this->getHubIdByMerchantId();
        if($id > 0) {
            $delivery = Delivery::find($id);
            if ($delivery->status > 1)
            {
                return 'i';
            }
        } else {
            $delivery = new Delivery();
            $delivery->consignment_id        = strtoupper(getUniqueConsignmentId(8));
        }
        $delivery->merchant_id           = $this->merchant_id;
        $delivery->recipient_name        = $input['recipient_name'];
        $delivery->recipient_number      = $input['recipient_number'];
        $delivery->recipient_email       = isset($input['recipient_email']) ? $input['recipient_email'] : '';
        $delivery->recipient_zone_id     = isset($input['recipient_zone_id']) ? $input['recipient_zone_id'] : 0;
        $delivery->recipient_address     = $input['recipient_address'];
        $delivery->latitude              = $input['latitude'];
        $delivery->longitude             = $input['longitude'];
        $delivery->package_description   = isset($input['package_description']) ? $input['package_description'] : '';
        $delivery->amount_to_be_collected = isset($input['amount_to_be_collected']) ? $input['amount_to_be_collected'] : 0;
        $delivery->charge                = $this->getCharge($input['plan_id']);
        $delivery->store_id              = isset($input['store_id']) ? $input['store_id'] : 0;
        $delivery->plan_id               = isset($input['plan_id']) ? $input['plan_id'] : 0;
        $delivery->plan_returned_id      = isset($input['plan_returned_id']) ? $input['plan_returned_id'] : 0;
        $delivery->delivery_note         = isset($input['delivery_note']) ? $input['delivery_note'] : '';
        $delivery->special_instruction   = isset($input['special_instruction']) ? $input['special_instruction'] : '';
        $delivery->merchant_order_id     = isset($input['merchant_order_id']) ? $input['merchant_order_id'] : '';
        $delivery->delivery_date         = isset($input['delivery_date']) && !empty($input['delivery_date'])? $input['delivery_date'] : null;
        $delivery->hub_id                = $hub_id;
        $delivery->status                = 1;
        $delivery->created_at            = date('Y-m-d H:i:s');
        if ($delivery->save()) {
            if (isset($input['products']) && !empty($input['products'])) {
                $this->deliveryProduct($input['products'], $delivery->id, $id);
            }
            // Save to details table
            storeTrackingData([
                'deliveries_id'     => $delivery->id,
                'flag_status_id'    => 1,
                'assign_to'         => 0,
                'is_hub'            => 0,
                'notes'             => '',
                'description'       => 'Your order has been successfully placed.',
                'in_out'            => 1,
                'hub_id'            => $hub_id
            ]);
            $find_rider = $this->findRiderByZone($input['recipient_zone_id']);
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
                if (empty($inputs['tokens'])) {
                    return 'f';
                }
                $this->fcm_notification->sendFCMNotification($inputs);
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
        $plan = DB::table('plans')
            ->select('charge')
            ->where([ 'id' => $plan_id ])
            ->first();
        if (!empty($plan)) {
            return $plan->charge;
        }
        return 0;
    }

    private function getHubIdByMerchantId() {
        $data = DB::table('merchants')
            ->select('hub_id')
            ->where([ 'id' => $this->merchant_id ])
            ->first();
        if (!empty($data)) {
            return $data->hub_id;
        }
        return 0;
    }

    private function deliveryProduct($products, $delivery_id, $id) {
        if($id > 0) {
           // Delete existing
            DB::table('delivery_product')->where('delivery_id', $delivery_id)->delete();
        }
        $products = explode(',', $products);
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
}
