<?php namespace App\Repositories\Admin\Invoice;


use App\DB\Admin\Delivery;
use App\DB\Admin\InvoiceBulkMail;
use App\DB\Admin\InvoiceDetails;
use App\DB\Admin\Invoices;
use Exception;
use Illuminate\Http\Request;
use App\DB\Admin\Invoice;
use DB;
use Datatables;

class EloquentInvoiceRepository implements InvoiceRepository
{
    protected $merchant_id;

    function __construct(Request $request)
    {
        $this->merchant_id = getMerchantId($request->header('Authorization'));
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getReportPaginated($request) {
        $from = $request->invoice_date;
        $to = $request->invoice_date;
        $date_range = $request->get('columns')[2]['search']['value'];
        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (date_validate($start_date)  || date_validate($end_date)) {
                $from = $start_date;
                $to = $end_date;
            }
        }
        if (!empty($request->query_string) && empty($date_range))
        {
            list($from, $to) = explode('~', $request->query_string);
        }
        $rows =  DB::table('deliveries as d')
            ->select(
                'd.invoice_date','d.id as deli_id','i.id as invoice_id','d.merchant_id','m.business_name',
                DB::raw('ROUND(COALESCE(SUM(CASE WHEN d.payment_status = 1 THEN d.receive_amount ELSE 0 END), 0)) AS collected'),
                DB::raw('ROUND(COALESCE(SUM(d.cod_charge), 0)) as cod'),
//                DB::raw('ROUND(COALESCE((SUM(d.charge) + SUM(d.cod_charge)), 0)) as fees'),
                DB::raw('ROUND(COALESCE((SUM(d.charge)), 0)) as fees'),
                DB::raw('COALESCE(SUM(CASE WHEN d.status = 6 THEN 1 ELSE 0 END), 0) AS delivered'),
                DB::raw('COALESCE(SUM(CASE WHEN d.status IN (7,8) THEN 1 ELSE 0 END), 0) AS returned'),
                DB::raw('ROUND(COALESCE(SUM(CASE WHEN d.payment_status = 1 THEN d.receive_amount - (d.charge + d.cod_charge + i.additional_amount) ELSE 0 END), 0)) AS paid'),
                DB::raw('COALESCE(CONCAT(m.first_name, " ", m.last_name)) AS full_name')
            )
            ->join('merchants as m','m.id','=','d.merchant_id')
            ->join('invoice_details as idls','idls.delivery_id', '=', 'd.id')
            ->join('invoices as i','i.id', '=', 'idls.invoice_id')
            ->join('tracking_details_summary as tds','tds.deliveries_id', '=', 'd.id')
            ->whereBetween('i.created_at',[$from.' 00:00:01',$to.' 23:59:59'])
            ->where('d.payment_status',1)
            ->orderBy('d.invoice_date', 'desc')
            ->groupBy('d.invoice_date','d.merchant_id','idls.invoice_id');
            if (get_admin_hub_id() > 0) {
                $rows = $rows->where(['tds.hub_id' => get_admin_hub_id()]);
            }


            return Datatables::of($rows)
                ->filterColumn('full_name', function($query, $keyword) {
                    $query->whereRaw("m.business_name like ?", ["%{$keyword}%"]);
                })
                ->addColumn('action_col', function ($user) {
                    $param = $user->merchant_id.",'".$user->invoice_date."'".",'".$user->invoice_id."'".",'paid'";
                    return
                        // <a href="#" onclick="InvoicesDetails('.$param.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Consignments"><i class="fa fa-cubes"></i></a>
                        '<a href="'.route('admin.invoice.notes',array($user->merchant_id)).'?invoice_type=paid&invoice_id='.$user->invoice_id.'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Notes"><i class="fa fa-eye"></i></a>
                    ';
                })
                ->make(true);
    }

    public function getUnInvoiceReportPaginated($request) {
        $from = $request->invoice_date;
        $to = $request->invoice_date;
        $date_range = $request->get('columns')[2]['search']['value'];
        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (date_validate($start_date)  || date_validate($end_date)) {
                $from = $start_date;
                $to = $end_date;
            }
        }
        $rows =  DB::table('deliveries as d')
            ->select(
                'd.invoice_date','d.id as deli_id','d.merchant_id','m.business_name',
                DB::raw('ROUND(COALESCE(SUM(CASE WHEN d.payment_status = 0 THEN d.receive_amount ELSE 0 END), 0)) AS collected'),
                DB::raw('ROUND(COALESCE(SUM(d.cod_charge), 0)) as cod'),
                DB::raw('ROUND(COALESCE((SUM(d.charge) + SUM(d.cod_charge)), 0)) as fees'),
                DB::raw('ROUND(COALESCE((SUM(d.charge)), 0)) as plan_charges'),
                DB::raw('COALESCE(SUM(CASE WHEN d.status IN (6,12) THEN 1 ELSE 0 END), 0) AS delivered'),
                DB::raw('COALESCE(SUM(CASE WHEN d.status IN (7,8,16) THEN 1 ELSE 0 END), 0) AS returned'),
                DB::raw('COALESCE(SUM(CASE WHEN d.status = 14 THEN 1 ELSE 0 END), 0) AS handover'),
                DB::raw('ROUND(COALESCE(SUM(CASE WHEN d.payment_status = 1 THEN d.receive_amount ELSE 0 END), 0)) AS paid'),
                DB::raw('COALESCE(CONCAT(m.first_name, " ", m.last_name)) AS full_name')
            )
            ->join('merchants as m','m.id','=','d.merchant_id')
            ->join('tracking_details_summary as tds','tds.deliveries_id', '=', 'd.id')
            //->whereBetween('d.invoice_date',[$from,$to])
            ->where('d.payment_status',0)
            ->whereIn('d.status',[6,8,12,14,16])
            ->orderBy('d.invoice_date', 'desc')
            ->groupBy('d.merchant_id');
        if (get_admin_hub_id() > 0) {
            $rows = $rows->where(['tds.hub_id' => get_admin_hub_id()]);
        }


        return Datatables::of($rows)
            ->filterColumn('full_name', function($query, $keyword) {
                $query->whereRaw("m.business_name like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action_col', function ($user) {
                $param = $user->merchant_id.",'".$user->invoice_date."'".",' '".",'unpaid'";
                return '
                    <a href="#" onclick="InvoicesDetails('.$param.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Consignments"><i class="fa fa-cubes"></i></a>
                    <a href="'.route('admin.invoice.notes',array($user->merchant_id)).'?invoice_type=unpaid" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Notes"><i class="fa fa-edit"></i></a>
                    ';
            })
            ->make(true);
    }

    public function findInvoice($request) {
        $rows =  DB::table('deliveries as d')
            ->select(
                'd.*',
                'fs.flag_text','fs.color_code',
                's.name as store_name',
                'p.plan_name',
                'cz.zone_name as recipient_zone_name',
                'merch.first_name','merch.last_name',
                'merch.business_name','merch.address'
            )
            ->join('flag_status as fs','fs.id', '=', 'd.status')
            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
            ->leftJoin('merchants as merch', 'merch.id', '=', 'd.merchant_id')
            ->whereIn('d.status',[6,8,12,14,16]);
        if ($request['invoice_type'] == "unpaid")
        {
            $rows = $rows->where([  'd.merchant_id' => $request['merchant_id'], 'd.payment_status' => 0 ]);
        } else {
            $rows = $rows->where([ 'd.invoice_date' => $request['invoice_date'], 'd.merchant_id' => $request['merchant_id'], 'd.payment_status' => 1 ]);
        }
        if (!empty($request['search_string']))
        {
            $rows = $rows->where(function ($query) use ($request) {
            $query->where('d.consignment_id', $request['search_string'])
                ->orWhere('d.merchant_order_id', $request['search_string']);
            });
        }
        if (!empty($request['date_range']))
        {
            $date = explode(' ~ ', $request['date_range']);
            $startDate = $date[0]." 00:00:01";
            $endDate = $date[1]." 23:59:59";
//            dd($startDate." ".$endDate);
            $rows = $rows->whereBetween('d.created_at',[$startDate, $endDate]);
        }
        $rows = $rows->orderBy('d.status', 'asc')
            ->get();
        return $rows;
    }

    public function findMerchantDeliveriesId($request) {
        $results = [];

        $rows =  DB::table('deliveries as d')
            ->select(
                'd.id AS delivery_id'
            )
            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
            ->leftJoin('merchants as merch', 'merch.id', '=', 'd.merchant_id');
        $rows = $rows->where([ 'd.invoice_date' => $request['invoice_date'], 'd.merchant_id' => $request['merchant_id'], 'd.payment_status' => 0 ]);
        $rows = $rows->orderBy('d.invoice_date', 'desc')
            ->lists('delivery_id');
        $results = [
            "invoice_date" => $request['invoice_date'],
            "merchant_id" => $request['merchant_id'],
            "delivery_id" => $rows,
            "notes" => '',
            "amount" => $request['collected'],
        ];
        return $results;
    }

    public function findPaidInvoice($request) {

        $rows =  DB::table('deliveries as d')
            ->select(
                'd.*',
                'fs.flag_text','fs.color_code',
                'i.id as invoices_id',
                'i.amount as invoice_amount','i.additional_amount',
                's.name as store_name',
                'p.plan_name',
                'cz.zone_name as recipient_zone_name',
                'merch.first_name','merch.last_name',
                'merch.business_name','merch.address'
            )
            ->join('flag_status as fs','fs.id', '=', 'd.status')
            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
            ->leftJoin('merchants as merch', 'merch.id', '=', 'd.merchant_id')
            ->join('merchants as m','m.id','=','d.merchant_id')
            ->join('invoice_details as idls','idls.delivery_id', '=', 'd.id')
            ->join('invoices as i','i.id', '=', 'idls.invoice_id')
        ;
        if ($request['invoice_type'] == "unpaid")
        {
            $rows = $rows->where([ 'd.invoice_date' => $request['invoice_date'], 'd.merchant_id' => $request['merchant_id'], 'd.payment_status' => 0 ]);
        } else {
            $rows = $rows->where([ 'd.merchant_id' => $request['merchant_id'], 'd.payment_status' => 1, "i.id" => $_GET['invoice_id'] ]);
        }
        $rows = $rows->orderBy('d.invoice_date', 'desc')
            ->get();
        return $rows;
    }

    public function storeInvoiceNotes($request)
    {
        date_default_timezone_set('Asia/Dhaka');

    if (!empty($request['delivery_id'])) {
        $inv = new Invoices();
        $inv->invoice_no = strtoupper(getUniqueInvoiceNumber(8));
        $inv->invoice_date = $request['invoice_date'];
        $inv->merchant_id = $request['merchant_id'];
        $inv->notes = $request['notes'];
        $inv->amount = $request['amount'];
        $inv->additional_amount = $request['additional_amount'];
        $inv->paid_status = 1;
        $inv->created_at = date("Y-m-d H:i:s");
        if ($inv->save()) {
        //$query = Delivery::select('id')->where(['merchant_id' => $inv->merchant_id, 'invoice_date' =>$inv->invoice_date])->get();
        foreach ($request['delivery_id'] as $val) {
            $del = Delivery::find($val);
            $del->payment_status = 1;
            $del->invoice_date = date("Y-m-d");
            //$del->save();
            if ($del->save()) {
                $invDetails = new InvoiceDetails();
                $invDetails->invoice_id = $inv->id;
                $invDetails->delivery_id = $val;
                $invDetails->created_at = date('Y-m-d H:i:s');
                $invDetails->updated_at = null;
                $invDetails->save();

            }
//                        DB::table('invoice_details')->insert(
//                            ['invoice_id' => $inv->id, 'delivery_id' => $val, 'created_at' => date('Y-m-d H:i:s')]
//                        );


        }
            return $inv->invoice_no;
    }

        }
    return 0;
    }

    public function merchantDetails($merchant_id)
    {
        return DB::table('merchants as m')
            ->select(
                "m.*",
                "mem.mobile_no",
                "mem.email as primary_mail")
            ->join("members as mem","mem.id","=","m.member_id")
            ->where("m.id",$merchant_id)
            ->first();
    }

    public function saveDataMongo($merchant_id, $invoice_id)
    {
        // TODO: Implement saveDataMongo() method.
        $mailInvoice = new InvoiceBulkMail;
        $mailInvoice->merchant_id = $merchant_id;
        $mailInvoice->invoice_id = $invoice_id;
        $mailInvoice->mail_status = 0;
        if ($mailInvoice->save())
        {
            return $mailInvoice->id;
        }
        return 0;

    }

}
