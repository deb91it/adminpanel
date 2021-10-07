<?php namespace App\Repositories\Api\Dashboard;

use Illuminate\Http\Request;
use DB;

class EloquentDashboardRepository implements DashboardRepository
{
    protected $merchant_id;

    function __construct(Request $request)
    {
        $this->merchant_id = getMerchantId($request->header('Authorization'));
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getCountedData($request)
    {
        if ($this->getMerchantUnInvoiced() > 0)
        {
            $que = DB::table('deliveries')
                ->select(
                    DB::raw('COALESCE(SUM(CASE WHEN ( status IN (6,8,12,16) AND payment_status = 0 ) THEN receive_amount ELSE 0 END), 0) AS total_sale'),
                    DB::raw('COALESCE(SUM(CASE WHEN ( status IN (6,12,16) AND payment_status = 0 ) THEN 1 ELSE 0 END), 0) AS total_delivered'),
                    DB::raw('COALESCE(SUM(CASE WHEN ( status IN (8) AND payment_status = 0 ) THEN 1 ELSE 0 END), 0) AS total_returned'),
                    DB::raw('COALESCE(SUM(CASE WHEN status IN (1,2,5,9,10,20) THEN 1 ELSE 0 END), 0) AS total_pending'),
                    DB::raw("(
                            SELECT 
                                COALESCE(ROUND(SUM(charge + cod_charge)), 0) 
                                FROM  
                                    deliveries 
                                WHERE 
                                    merchant_id = {$this->merchant_id} AND 
                                    status IN (6,8,12,16) AND 
                                    payment_status = 0                             
                        ) as total_shipping")
                )
                ->where([ 'merchant_id' =>  $this->merchant_id ]);
            if (isset($request->from_date) && !empty($request->from_date))
            {
                $que = $que->whereBetween('created_at',[ $request->from_date." 00:00:01", $request->to_date." 23:59:59"  ]);
            }
            $que = $que->first();
            $que->total_uninvoiced = $que->total_sale - $que->total_shipping;
            return $que;

        } else {
            $que = DB::table('deliveries')
                ->select(
                    DB::raw('COALESCE(SUM(CASE WHEN ( status IN (6,8,12,16) AND payment_status = 1 ) THEN receive_amount ELSE 0 END), 0) AS total_sale'),
                    DB::raw('COALESCE(SUM(CASE WHEN ( status IN (6,12,16) AND payment_status = 1 ) THEN 1 ELSE 0 END), 0) AS total_delivered'),
                    DB::raw('COALESCE(SUM(CASE WHEN ( status IN (8) AND payment_status = 1 ) THEN 1 ELSE 0 END), 0) AS total_returned'),
                    DB::raw('COALESCE(SUM(CASE WHEN status IN (1,2,3,20) THEN 1 ELSE 0 END), 0) AS total_pending'),
                    DB::raw("(
                            SELECT 
                                COALESCE(ROUND(SUM(charge + cod_charge)), 0)
                                FROM  
                                    deliveries 
                                WHERE 
                                    merchant_id = {$this->merchant_id} AND 
                                    status IN (6,8,12,16) AND 
                                    payment_status = 1                             
                        ) as total_shipping")
                )
                ->where([ 'merchant_id' =>  $this->merchant_id ]);
                if (isset($request->from_date) && !empty($request->from_date))
                {
                    $que = $que->whereBetween('created_at',[ $request->from_date." 00:00:01", $request->to_date." 23:59:59"  ]);
                }
            $que = $que->first();
            $que->total_uninvoiced = 0;
            return $que;
        }
    }

    public function getMerchantUnInvoiced()
    {
        return DB::table('deliveries')
            ->where([ 'merchant_id' =>  $this->merchant_id,  'status' => 6, 'payment_status' => 0])
            ->count();

    }

    public function getGraphData($request)
    {
        $return_arr = [];
        $deliver_arr = [];
        $results = [];
        $from =  date("Y-m-d", strtotime('-14 days'));
        $to =  date("Y-m-d");

        $dates = getAllDateOfRangePeriod($from, $to);
        if(empty($dates)){
            return [];
        }

        $from = $dates[0].' 00:00:00';
        $to = $dates[count($dates)-1].' 23:59:59';

        $delivery = DB::table('deliveries')
            ->select(
                'delivery_date',
                DB::raw('COALESCE(SUM(CASE WHEN status = 6 THEN 1 ELSE 0 END), 0) AS delivered')
            )
            ->groupBy(DB::raw('delivery_date'))
            ->where([ 'merchant_id' =>  $this->merchant_id ])
            ->whereBetween('delivery_date', [$from, $to])
            ->get();


        if (!empty($delivery)) {
            foreach ($delivery as $key => $val) {
                $deliver_arr[$val->delivery_date] = $val->delivered;
            }
        }

        $returned = DB::table('deliveries')
            ->select(
                'return_date',
                DB::raw('COALESCE(SUM(CASE WHEN status = 8 THEN 1 ELSE 0 END), 0) AS returned')
            )
            ->groupBy(DB::raw('return_date'))
            ->where( [ 'merchant_id' =>  $this->merchant_id ])
            ->whereBetween('return_date', [$from, $to])
            ->get();

        if (!empty($returned)) {
            foreach ($returned as $index => $item) {
                $return_arr[$item->return_date] = $item->returned;
            }
        }

        foreach ($dates as $key => $date) {
            $single_arr = [];
            $single_arr['date'] = $date;

            if (array_key_exists($date, $return_arr)) {
                $single_arr['returned'] = (int) $return_arr[$date];
            } else {
                $single_arr['returned'] = 0;
            }

            if (array_key_exists($date, $deliver_arr)) {
                $single_arr['delivered'] = (int) $deliver_arr[$date];
            } else {
                $single_arr['delivered'] = 0;
            }

            $results[] = $single_arr;
        }

        return $results;
    }
}
