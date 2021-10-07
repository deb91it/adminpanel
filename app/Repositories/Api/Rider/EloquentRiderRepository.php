<?php namespace App\Repositories\Api\Rider;

use App\DB\Api\Agent;
use App\DB\Driver;
use App\DB\Member;
use App\DB\DeviceRegistration;
use DB;

/**
 * Class EloquentZonesRepository
 * @package App\Repositories\Rider
 */
class EloquentRiderRepository implements RiderRepository
{

    /**
     * @var Rider
     */
    protected $rider;

    /**
     * EloquentZonesRepository constructor.
     * @param Rider $rider
     */
    function __construct(Agent $rider)
    {
        $this->rider = $rider;
        date_default_timezone_set('Asia/Dhaka');
    }

    /**
     * @param array $ar_filter_params
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @param $id
     * @param int $status
     * @return mixed
     */
    public function getById($id, $status = 1)
    {
        // TODO: Implement getById() method.
    }

    /**
     * @param $inputs
     * @return mixed
     */
    public function create($inputs)
    {
        // TODO: Implement create() method.
    }

    public function getPickupList($request, $per_page = 50) {
        $resultArr = [];
        $ext_query = '';
        $zone_id = $request["zone_id"];
        $lat1 = $request["rider_lat"];
        $lon1 = $request["rider_lon"];
        $fromDate = $request['from_date']." 00:00:01";
        $toDate = $request['to_date']." 23:59:59";
        if (!empty($request['from_date']) && !empty($request['to_date']))
        {
            $ext_query = " AND d.created_at BETWEEN '{$fromDate}' AND '{$toDate}'";
        }
        $flag_status = '1,2,3,4,5,6,7,8,9,10,11';
        if (isset($request['flag_status']) && !empty($request['flag_status']))
        {
            $flag_status = ($request['flag_status']);
        }
        $merchants = DB::select(
            "SELECT
            ms.id, d.consignment_id, ms.business_name, d.status
        FROM
            deliveries AS d
                JOIN
            merchants AS ms ON ms.id = d.merchant_id
                JOIN
            members AS m ON m.id = ms.member_id
                JOIN
            flag_status AS fs ON fs.id = d.status
        WHERE
            d.recipient_zone_id = {$zone_id}
                AND d.status IN ({$flag_status})
                {$ext_query}
        GROUP BY d.merchant_id
        ORDER BY d.id ASC
        ;"
        );


        $rows =  DB::select(
            "SELECT
                d.*,
                CONCAT(ms.first_name, ' ', ms.last_name) AS merchant_name,
                ms.address AS merchant_address,
                ms.cod_percentage AS merchant_cod_percentage,
                ms.business_name AS merchant_business_name,
                m.email AS merchant_email,
                m.mobile_no AS merchant_contact,
                fs.flag_text AS status_text,
                fs.color_code AS status_color,
                s.name AS store_name,
                COALESCE(s.latitude, 0) AS store_latitude,
                COALESCE(s.longitude, 0) AS store_longitude,
                cz.zone_name AS recipient_zone_name,
                p.plan_name,
                (SELECT
                        SUM(weight)
                    FROM
                        products
                    WHERE
                        id IN (SELECT
                                product_id
                            FROM
                                delivery_product
                            WHERE
                                delivery_id = d.id)) AS consignment_weight
            FROM
                deliveries AS d
                    JOIN
                merchants AS ms ON ms.id = d.merchant_id
                    JOIN
                members AS m ON m.id = ms.member_id
                    JOIN
                flag_status AS fs ON fs.id = d.status
                    LEFT JOIN
                stores AS s ON s.id = d.store_id
                    LEFT JOIN
                plans AS p ON p.id = d.plan_id
                    LEFT JOIN
                courier_zones AS cz ON cz.id = d.recipient_zone_id
            WHERE
                d.recipient_zone_id = {$zone_id}
                    AND d.status IN ({$flag_status})
                    {$ext_query}
            ORDER BY d.id ASC"
        );


        if(!empty($rows)) {
            foreach ($rows as $key => $row) {
                $row->distance = $this->calculateDistance($lat1, $lon1, $lat2 = $row->store_latitude, $lon2 = $row->store_longitude, $unit = 'k');
            }
        }

        if(!empty($merchants))
        {
            foreach ($merchants as $key => $m)
            {
                $mlists = [];
                $resultArr[$key]['merchant_id'] = $m->id;
                $resultArr[$key]['merchant_business_name'] = $m->business_name;
                foreach ($rows as $val)
                {
                    if ($val->merchant_id == $m->id)
                    {
                        array_push($mlists, $val);
                    }
                }
                $resultArr[$key]['merchant_items_list'] = $mlists;
            }
        }
        //$resultArr = collect([$resultArr]);
        return $resultArr;
    }

    public function getDeliveryLists($request, $per_page) {

        

        $extension_query = '';
        $lat1 = $request["rider_lat"];
        $lon1 = $request["rider_lon"];
        $flag_status = array(6,7,8,9,10);
        if (isset($request['flag_status']) && !empty($request['flag_status']))
        {
            $explodeStatus = explode(",", $request['flag_status']);
            $flag_status = $explodeStatus;
        }
        $from_date = $request["from_date"]." 00:00:01";
        $to_date = $request["to_date"]." 23:59:59";
        if (!empty($request["from_date"]) && !empty($request["to_date"]))
        {
            $extension_query = ' created_at between "'.$from_date.'" AND "'.$to_date.'" AND ';
        }

        $rows =  DB::table('deliveries as d')
            ->select(
                DB::raw('CONCAT(ms.first_name, " ", ms.last_name) AS merchant_name'),
                'ms.address as merchant_address',
                'ms.business_name as merchant_business_name',
                'ms.cod_percentage as merchant_cod_percentage',
                'm.email as merchant_email',
                'm.mobile_no as merchant_contact',
                'd.*',
                'fs.flag_text as status_text', 'fs.color_code as status_color',
                's.name as store_name',
                'p.plan_name',
                'cz.zone_name as recipient_zone_name',
                DB::raw('(SELECT SUM(weight) FROM products WHERE id IN (SELECT product_id FROM delivery_product WHERE delivery_id = d.id) ) AS consignment_weight')
            )
            ->join('flag_status as fs', 'fs.id', '=', 'd.status');
            if (isset($request['flag_status']) && !empty($request['flag_status']) && isset($request['filter_type']) && empty($request['filter_type']))
	        {
	            $rows = $rows->join(DB::raw('
            (
            select * from tracking_details 
            where '.$extension_query.'
                assign_to = '.$request["user_id"].'
            AND
                flag_status_id IN ('.$request["flag_status_id"].')
            AND
                is_hub = '.$request["is_hub"].'
            AND
                is_active = 1
            ) as td'), 'd.id', '=', 'td.deliveries_id');
	        }

            $rows = $rows->join('merchants as ms', 'ms.id', '=', 'd.merchant_id')
            ->join('members as m', 'm.id', '=', 'ms.member_id')
            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
            ;
//        if (!empty($request['from_date']) && !empty($request['to_date']))
//        {
//            $rows = $rows->whereBetween( 'd.created_at', [$request['from_date'],$request['to_date']] );
//        }

        if (isset($request['filter_type']) && !empty($request['filter_type']))
        {
            $rows = $rows->where(function ($query) use ($request) {
                $query->where('d.consignment_id', $request['filter_type'])
                    ->orWhere('d.recipient_name', 'like', '%'.$request['filter_type'].'%')
                    ->orWhere('d.merchant_order_id', 'like', '%'.$request['filter_type'].'%')
                    ->orWhere('d.recipient_number', 'like', '%'.$request['filter_type'].'%');
            });
        }else{

            $rows = $rows->whereIN('d.status',$flag_status);
        }
        $rows = $rows->orderBy('d.id', 'desc')
            ->paginate($per_page);

//        if(!empty($rows)) {
//            foreach ($rows as $key => $row) {
//                $row->distance = $this->calculateDistance($lat1, $lon1, $lat2 = $row->latitude, $lon2 = $row->longitude, $unit = 'k');
//            }
//        }

        return $rows;
    }

    public function getProductListsByConsignmentId($delivery_id, $per_page) {

        $rows =  DB::table('delivery_product as dp')
            ->select(
                'd.id as delivery_id','d.consignment_id',
                'fs.flag_text as status_text', 'fs.color_code as status_color',
                's.name as store_name',
                'c.name as category_name',
                DB::raw('CONCAT(m.first_name, " ", m.last_name) as merchant_name'),
                'p.*'

            )
            ->join('products as p', 'p.id', '=', 'dp.product_id')
            ->join('deliveries as d', 'd.id', '=', 'dp.delivery_id')
            ->leftjoin('merchants as m', 'm.id', '=', 'p.merchant_id')
            ->leftjoin('stores as s', 's.id', '=', 'p.store_id')
            ->leftjoin('categories as c', 'c.id', '=', 'p.category_id')
            ->join('flag_status as fs', 'fs.id', '=', 'd.status')
            ->where([ 'dp.delivery_id' => $delivery_id ]);
//        if (!empty($request['from_date']) && !empty($request['to_date']))
//        {
//            $rows = $rows->whereBetween( 'd.created_at', [$request['from_date'],$request['to_date']] );
//        }


        $rows = $rows->orderBy('d.id', 'desc')
            ->paginate($per_page);

//        if(!empty($rows)) {
//            foreach ($rows as $key => $row) {
//                $row->payment_status = $row->payment_status === 0 ? 'Unpaid' : 'Paid';
//                $row->products = $this->getProductsByDelivery($row->id);
//            }
//        }

        return $rows;
    }

    public function getFlagStatus($inputs) {

        $rows =  DB::table('flag_status as fg')
            ->select(
                'fg.*'
            );

        if (!empty($inputs->get('user_type')) && $inputs->get('user_type') == 1)
        {
            $rows = $rows->whereIN('id',[6,8,9,12,16,20]);
        }else{
            $rows = $rows->whereIN('id',[1,2,5,6,8,9,10,16,20]);
        }
        $rows = $rows->orderBy('fg.id', 'ASC')
            ->get();

        return $rows;
    }

    public function storeTrackingDetails($input)
    {

        $track = [];
        $desc = '';
        switch ($input['flag_status_id']){
            case 1: $desc = 'Pending.';
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

            case 11: $desc = 'Order has been collected from Merchant.';
                break;

        }

        if ($input['flag_status_id'] == 10 || $input['flag_status_id'] == 11)
        {
            $msg = $input['flag_status_id'] == 11 ? 'Order has been collected from Merchant - by  ' : 'Assigned to ';
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
                $desc = !empty($assign) ? 'Your order is in transit.': '';
            }
        }
        $assign_to = !empty($input['assign_to']) ? $input['assign_to'] : 0;
        if (isset($input['user_id']) && !empty($input['user_id'])) {
           $assign_to = $input['user_id'];
        }
        if ($input['flag_status_id'] == 11 && isset($input['user_id']))
        {
            $assign_to = $input['user_id'];
        }
        $is_hub = !empty($input['is_hub']) ? $input['is_hub'] : 0;
        $in_out = $input['is_hub'] == 1 ? 2 : 1;
        $flag_status_id = $input['is_hub'] == 1 ? 5 : $input['flag_status_id'];

        $hub = DB::table('tracking_details_summary')->select('hub_id')->where('deliveries_id',$input['deliveried_id'])->first();
        $track['deliveries_id'] = $input['deliveried_id'];
        $track['flag_status_id'] = $flag_status_id;
        $track['assign_to'] = $assign_to;
        $track['is_hub'] = $is_hub;
        $track['notes'] = $input['notes'];
        $track['receive_amount'] = $input['receive_amount'];
        $track['description'] = $desc;
        $track['in_out'] = $in_out;
        $track['hub_id'] = $input['is_hub'] == 1 ? $input['assign_to'] : '';
        $track['rescheduled_date'] = array_key_exists("new_date", $input) ? $input['new_date'] : '';
        storeTrackingData($track);
        if($input['flag_status_id'] == 3){
            return $input['deliveried_id'];
        }
        return 1;
    }

    public function getPickUpDashboard($request) {
        $formDate = date("Y-m-d 00:00:01");
        $toDate = date("Y-m-d 23:59:59");
        $flag_status = array(1,2,11);

        if (!empty($request["fromDate"]) && !empty($request["toDate"]))
        {
            $formDate = $request["fromDate"]." 00:00:01";
            $toDate = $request["toDate"]." 23:59:59";
        }
        //echo $formDate ." ". $toDate;exit();

        $rows =DB::table('deliveries')
        ->select(
            DB::raw('(SELECT 
                        COALESCE(COUNT(d.id), 0)
                    FROM
                        tracking_details AS td
                            JOIN
                        deliveries AS d ON d.id = td.deliveries_id
                    WHERE
                        td.assign_to = '.$request["user_id"].'
                            AND td.flag_status_id IN (21)
                            AND td.created_at BETWEEN "'.$formDate.'" AND "'.$toDate.'") AS today_pickup'),
            DB::raw('(SELECT 
                        COALESCE(COUNT(d.id), 0)
                    FROM
                        tracking_details AS td
                            JOIN
                        deliveries AS d ON d.id = td.deliveries_id
                    WHERE
                        td.assign_to = '.$request["user_id"].'
                            AND td.flag_status_id IN (11)
                            AND td.created_at BETWEEN "'.$formDate.'" AND "'.$toDate.'") AS today_visit')
//            DB::raw('(select COUNT(id) FROM deliveries WHERE status = 11 AND recipient_zone_id = '.$request["user_id"].' AND created_at BETWEEN "'.$formDate.'" AND "'.$toDate.'") AS today_visit'),
//            DB::raw('(SELECT COALESCE(SUM(p.weight), 0) FROM products as p
//                        JOIN delivery_product as dp on p.id = dp.product_id
//                        JOIN deliveries as d ON d.id = dp.delivery_id
//                        WHERE d.recipient_zone_id = '.$request["zone_id"].'
//                        AND d.created_at BETWEEN "'.$formDate.'" AND "'.$toDate.'") AS today_quantity'),

//            DB::raw('(select COUNT(id) FROM deliveries WHERE status = 11 AND recipient_zone_id = '.$request["zone_id"].' AND created_at BETWEEN "'.$formDate.'" AND "'.$toDate.'") AS today_received'),
//            DB::raw('(
//                        (SELECT
//                            COALESCE(COUNT(d.id), 0)
//                        FROM
//                            tracking_details AS td
//                                JOIN
//                            deliveries AS d ON d.id = td.deliveries_id
//                        WHERE
//                            d.recipient_zone_id = '.$request["zone_id"].'
//                                AND td.flag_status_id IN (1)
//                                AND td.created_at BETWEEN "'.$formDate.'" AND "'.$toDate.'"
//            )-(select COUNT(id) FROM deliveries WHERE status NOT IN (1) AND recipient_zone_id = '.$request["zone_id"].' AND created_at BETWEEN "'.$formDate.'" AND "'.$toDate.'")) AS today_pending')

        )->first();

        $rows->today_pickup = $rows->today_pickup - $rows->today_visit;
        $rows->today_quantity = $rows->today_pickup;
        $rows->today_received = $rows->today_visit;
        $rows->today_received = $rows->today_visit;
        $rows->today_pending = $rows->today_pickup - $rows->today_received;

        return $rows;
    }

    public function getDeliveryDashboard($request) {

        $extension_query = '';
        $from_date = date("Y-m-d 00:00:01");
        $to_date = date("Y-m-d 23:59:59");

        if (!empty($request["from_date"]) && !empty($request["to_date"]))
        {
            $from_date = $request["from_date"]." 00:00:01";
            $to_date = $request["to_date"]." 23:59:59";
            $extension_query = ' created_at between "'.$from_date.'" AND "'.$to_date.'" AND ';
        }
        $rows =  DB::table('deliveries as d')
            ->select(
                DB::raw("(SELECT 
                        COUNT(id)
                    FROM
                        tracking_details
                    WHERE
                        flag_status_id = 10 AND assign_to = {$request["user_id"]}
                            AND created_at BETWEEN '{$from_date}' AND '{$to_date}') AS today_collected"),
                DB::raw("(SELECT 
                        COUNT(d.id)
                    FROM
                        tracking_details as td
                    JOIN
                        deliveries as d
                    ON 
                        d.id = td.deliveries_id
                    WHERE
                        d.status IN (6, 7, 8, 9, 12, 16, 20) AND td.flag_status_id IN (6, 7, 8, 9, 12, 16, 20)
                            AND td.assign_to = {$request["user_id"]}
                            AND td.created_at BETWEEN '{$from_date}' AND '{$to_date}') AS today_visited"),
                DB::raw("(SELECT 
                        COUNT(d.id)
                    FROM
                        tracking_details as td
                    JOIN
                        deliveries as d
                    ON 
                        d.id = td.deliveries_id
                    WHERE
                        d.status IN (7, 8) AND td.flag_status_id IN (7,8) AND td.assign_to = {$request["user_id"]}
                            AND td.created_at BETWEEN '{$from_date}' AND '{$to_date}') AS today_returned"),
                DB::raw("(SELECT 
                        COUNT(d.id)
                    FROM
                        tracking_details as td
                    JOIN
                        deliveries as d
                    ON 
                        d.id = td.deliveries_id
                    WHERE
                        d.status IN (9,20) AND td.flag_status_id IN (9,20) AND  td.assign_to = {$request["user_id"]}
                            AND td.created_at BETWEEN '{$from_date}' AND '{$to_date}') AS today_on_hold"),
                DB::raw("(SELECT 
                        COUNT(d.id)
                    FROM
                        tracking_details as td
                    JOIN
                        deliveries as d
                    ON 
                        d.id = td.deliveries_id
                    WHERE
                        d.status IN (6,12,16) AND td.flag_status_id IN (6,12,16) AND td.assign_to = {$request["user_id"]}
                            AND td.created_at BETWEEN '{$from_date}' AND '{$to_date}') AS today_delivered"),
                DB::raw("(SELECT 
                        COALESCE(SUM(receive_amount), 0)
                    FROM
                        deliveries AS d
                            JOIN
                        tracking_details AS td ON d.id = td.deliveries_id
                    WHERE
                        td.created_at BETWEEN '{$from_date}' AND '{$to_date}'
                            AND td.assign_to = {$request["user_id"]}
                            AND td.flag_status_id IN (6 , 7, 8, 12, 16)) AS today_collected_amount")
            );


        $rows = $rows->orderBy('d.id', 'desc')
            ->first();

        $rows->today_collected = $rows->today_collected - $rows->today_visited;

        return $rows;
    }

    protected function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }


}
