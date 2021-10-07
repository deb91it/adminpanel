<?php namespace App\Repositories\Api\Invoice;


use Illuminate\Http\Request;
use App\DB\Api\Invoice;
use DB;

class EloquentInvoiceRepository implements InvoiceRepository
{
    protected $merchant_id;

    function __construct(Request $request)
    {
        $this->merchant_id = getMerchantId($request->header('Authorization'));
        date_default_timezone_set('Asia/Dhaka');
    }

//    public function getInvoiceList($per_page = 20) {
//        $rows =  DB::table('deliveries as d')
//            ->select(
//                'd.invoice_date',
//                DB::raw('COALESCE(SUM(CASE WHEN d.payment_status = 1 THEN d.receive_amount ELSE 0 END), 0) AS collected'),
//                DB::raw('COALESCE(SUM(d.cod_charge), 0) as cod'),
//                DB::raw('COALESCE((SUM(d.charge) + SUM(d.cod_charge)), 0) as fees'),
//                DB::raw('COALESCE(SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END), 0) AS delivered'),
//                DB::raw('COALESCE(SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END), 0) AS returned'),
//                DB::raw('COALESCE(SUM(CASE WHEN d.payment_status = 1 THEN d.receive_amount - (d.charge + d.cod_charge) ELSE 0 END), 0) AS paid')
//            )
//            ->where([ 'd.merchant_id' => $this->merchant_id, 'd.payment_status' => 1 ])
//            ->orderBy('d.invoice_date', 'desc')
//            ->groupBy(DB::raw('d.invoice_date'))
//            ->paginate($per_page);
//
//        return $rows;
//    }
    public function getInvoiceList($per_page = 20) {
        $rows =  DB::table('deliveries as d')
            ->select(
                'd.invoice_date','d.id as deli_id','i.id as invoice_id','d.merchant_id','m.business_name','i.amount AS paid',
                DB::raw('ROUND(COALESCE(SUM(CASE WHEN d.payment_status = 1 THEN d.receive_amount ELSE 0 END), 0)) AS collected'),
                DB::raw('ROUND(COALESCE(SUM(d.cod_charge), 0)) as cod'),
                DB::raw('ROUND(COALESCE((SUM(d.charge) + SUM(d.cod_charge) +  i.additional_amount), 0)) as fees'),
                DB::raw('COALESCE(SUM(CASE WHEN d.status = 6 THEN 1 ELSE 0 END), 0) AS delivered'),
                DB::raw('COALESCE(SUM(CASE WHEN d.status IN (7,8) THEN 1 ELSE 0 END), 0) AS returned'),
//                DB::raw('ROUND(COALESCE(SUM(CASE WHEN d.payment_status = 1 THEN d.receive_amount - (d.charge + d.cod_charge + i.additional_amount) ELSE 0 END), 0)) AS paid'),
                DB::raw('COALESCE(CONCAT(m.first_name, " ", m.last_name)) AS full_name')
            )
            ->join('merchants as m','m.id','=','d.merchant_id')
            ->join('invoice_details as idls','idls.delivery_id', '=', 'd.id')
            ->join('invoices as i','i.id', '=', 'idls.invoice_id')
            ->join('tracking_details_summary as tds','tds.deliveries_id', '=', 'd.id')
//            ->whereBetween('i.created_at',[$from.' 00:00:01',$to.' 23:59:59'])
            ->where([ 'd.merchant_id' => $this->merchant_id, 'd.payment_status' => 1 ])
            ->orderBy('d.invoice_date', 'desc')
            ->groupBy('d.invoice_date','d.merchant_id','idls.invoice_id')
            ->paginate($per_page);

        return $rows;
    }

    public function getInvoiceStatistics()
    {
        // TODO: Implement getInvoiceStatistics() method.
        $que = DB::table('deliveries')
            ->select(
                DB::raw('count(id) AS total_parcel'),
                DB::raw('COALESCE(ROUND(SUM(CASE WHEN ( status IN (6,8,12,16)) THEN receive_amount ELSE 0 END)), 0) AS total_sale'),
                DB::raw('COALESCE(ROUND(SUM(CASE WHEN ( status IN (6,8,12,16) AND payment_status = 0 ) THEN receive_amount ELSE 0 END)), 0) AS total_uninvoiced'),
                DB::raw('COALESCE(ROUND(SUM(CASE WHEN ( status IN (6,12,16)) THEN 1 ELSE 0 END)), 0) AS total_delivered'),
                DB::raw('COALESCE(ROUND(SUM(CASE WHEN ( status IN (8)) THEN 1 ELSE 0 END)), 0) AS total_returned'),
                DB::raw('COALESCE(ROUND(SUM(CASE WHEN status IN (1,2,5,9,10,20) THEN 1 ELSE 0 END)), 0) AS total_pending'),
                DB::raw("(
                            SELECT 
                                COALESCE(ROUND(SUM(charge + cod_charge)), 0) 
                                FROM  
                                    deliveries 
                                WHERE 
                                    merchant_id = {$this->merchant_id} AND 
                                    status IN (6,8,12,16) AND 
                                    payment_status = 1                             
                        ) as total_shipping"),
                DB::raw("(
                            SELECT 
                                COALESCE(ROUND(SUM(amount)),0)
                            FROM
                                invoices
                            WHERE
                                merchant_id = {$this->merchant_id}
                        ) AS total_invoiced_amount")
            )
            ->where([ 'merchant_id' =>  $this->merchant_id ]);
        $que = $que->first();
//        $que->total_uninvoiced = $que->total_sale- $que->total_invoiced_amount;
        return $que;
    }
//    public function findInvoice($invoiceID) {
//        $rows =  DB::table('deliveries as d')
//            ->select(
//                'd.*',
//                'd.cod_charge as cod',
//                'fs.flag_text as status_text',
//                'fs.color_code as status_color',
//                DB::raw("d.charge + d.cod_charge as total_charge"),
//                's.name as store_name',
//                'p.plan_name',
//                'cz.zone_name as recipient_zone_name'
//            )
//            ->join('flag_status as fs', 'fs.id', '=', 'd.status')
//            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
//            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
//            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
//            ->leftJoin('merchants as merch', 'merch.id', '=', 'd.merchant_id')
//            ->join('merchants as m','m.id','=','d.merchant_id')
//            ->join('invoice_details as idls','idls.delivery_id', '=', 'd.id')
//            ->join('invoices as i','i.id', '=', 'idls.invoice_id')
//            ->where([ 'd.merchant_id' => $this->merchant_id, 'd.payment_status' => 1, "i.id" => $invoiceID ])
//            ->orderBy('d.invoice_date', 'desc')
//            ->get();
//        return $rows;
//    }
    public function findInvoice($invoiceID) {
        $rows =  DB::table('deliveries as d')
            ->select(
                'd.*',
                'd.cod_charge as cod',
                'fs.flag_text as status_text',
                'fs.color_code as status_color',
                DB::raw("d.charge + d.cod_charge as total_charge"),
                's.name as store_name',
                'p.plan_name',
                'cz.zone_name as recipient_zone_name',
                'i.invoice_no',
                'i.additional_amount',
                'i.amount',
                'i.notes'
            )
            ->join('flag_status as fs', 'fs.id', '=', 'd.status')
            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
            ->leftJoin('merchants as merch', 'merch.id', '=', 'd.merchant_id')
            ->join('merchants as m','m.id','=','d.merchant_id')
            ->join('invoice_details as idls','idls.delivery_id', '=', 'd.id')
            ->join('invoices as i','i.id', '=', 'idls.invoice_id')
            ->where([ 'd.merchant_id' => $this->merchant_id, 'd.payment_status' => 1, "i.id" => $invoiceID ])
            ->orderBy('d.invoice_date', 'desc')
            ->get();
        return $rows;
    }
}
