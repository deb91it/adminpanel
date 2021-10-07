<?php namespace App\Repositories\Admin\FinancialStatement;

use App\DB\Admin\Expense;
use Illuminate\Support\Facades\DB;
use Datatables;

class EloquentFinancialStatementRepository implements FinancialStatementRepository
{
    public function getReportPaginated($request)
    {
        $filter_status = $request->get('columns')[3]['search']['value'];
        $date_range = $request->get('columns')[5]['search']['value'];
        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (date_validate($start_date)  || date_validate($end_date)) {
                $from = $start_date;
                $to = $end_date;
            }
        }
        $query = DB::table('deliveries as d')
            ->select(
                "d.*",'fs.flag_text','fs.color_code',
                DB::raw("(CONCAT(m.first_name,' ',m.last_name)) AS merchant_full_name"),
                DB::raw("(CASE
                            WHEN d.status = 6 THEN d.delivery_date
                        ELSE d.return_date
                        END) AS income_date"),
                DB::raw("(CASE
                            WHEN d.payment_status = 1 THEN (d.receive_amount - (d.charge + d.cod_charge))
                        ELSE 0
                        END) AS paid_amount"),
                DB::raw("(CASE
                            WHEN d.payment_status = 0 THEN (d.receive_amount - (d.charge + d.cod_charge))
                        ELSE 0
                        END) AS unpaid_amount")
            )
            ->join('flag_status as fs','fs.id', '=', 'd.status')
            ->join('merchants as m','m.id', '=', 'd.merchant_id');
        if (!empty($filter_status)) {
            $query = $query->whereIn("d.status", [$filter_status]);
        }else{
            $query = $query->whereIn("d.status", [6, 7, 8]);
        }
        $query = $query->whereNotIn("d.merchant_id", [1])
            ->groupBy('d.id')
            ->orderBy('income_date','desc');
        
        $total = DB::table('deliveries as d')
            ->select(
                DB::raw('IFNULL(sum(d.charge), 0) as charge'),
                DB::raw('IFNULL(sum(d.cod_charge), 0) as cod_charges'),
                DB::raw('IFNULL(sum((CASE
                            WHEN d.payment_status = 1 THEN (d.receive_amount - (d.charge + d.cod_charge))
                        ELSE 0
                        END)), 0) AS total_paid_amount'),
                DB::raw('IFNULL(sum((CASE
                            WHEN d.payment_status = 0 THEN (d.receive_amount - (d.charge + d.cod_charge))
                        ELSE 0
                        END)), 0) AS total_unpaid_amount')
            )
            ->join('flag_status as fs','fs.id', '=', 'd.status');
            if (!empty($filter_status)) {
                $total = $total->whereIn("d.status", [$filter_status]);
            }else{
                $total = $total->whereIn("d.status", [6, 7, 8]);
            }
            $total = $total->whereNotIn("d.merchant_id", [1]);

        if (!empty($date_range))
        {
            $inCase = "
            CASE
                WHEN d.status = 6 THEN d.delivery_date
            ELSE d.return_date
            END between '{$from}' AND '{$to}'";
            $query = $query->whereRaw($inCase);
            $total = $total->whereRaw($inCase)->first();
        }

        if (!empty($request->income_date) && empty($date_range))
        {
            $inCase = "
            CASE
                WHEN d.status = 6 THEN d.delivery_date
            ELSE d.return_date
            END between '{$request->income_date}' AND '{$request->income_date}'";
            $query = $query->whereRaw($inCase);
            $total = $total->whereRaw($inCase)->first();
        }
        return Datatables::of($query, $total)
            ->filterColumn('consignment_id', function($query, $keyword) {
                $query->whereRaw("d.consignment_id like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('merchant_full_name', function($query, $keyword) {
                $query->whereRaw("merchant_full_name like ?", ["%{$keyword}%"]);
            })
            ->addColumn('total_income', function ($user) {
                return (double) ($user->charge + $user->cod_charge);
            })
            ->addColumn('income_date', function ($user) {
                return  date("d M, Y h:i a", strtotime($user->income_date));
            })
            ->with('totalCharge', round($total->charge))
            ->with('totalCod', round($total->cod_charges))
            ->with('totalIncome', round($total->charge + $total->cod_charges))
            ->with('totalPaidMerchant', round($total->total_paid_amount))
            ->with('totalUnPaidMerchant', round($total->total_unpaid_amount))
            ->make(true);
    }

    public function getIncomeStatement($request)
    {
        $from = $request->from_date;
        $to = $request->to_date;
        if (isset($request->query_string) && !empty($request->query_string))
        {
            list($from, $to) = explode("~", $request->query_string);
        }
        $dates = getAllDateOfRangePeriod($from, $to);
        foreach ($dates as $key => $date) {
            $single_arr = [];
            $single_arr['income'] = round($this->Income($from = $date, $to = $date));
            $single_arr['date'] = $date;
            $results[] = $single_arr;
        }
        return $results;
    }

    public function Income($from, $to)
    {
        $inCase = "
            CASE
                WHEN d.status = 6 THEN d.delivery_date
            ELSE d.return_date
            END = '{$from}'";
        $query = DB::table('deliveries as d')
            ->join('flag_status as fs','fs.id', '=', 'd.status')
            ->whereIn("d.status", [6, 7, 8])
            ->whereNotIn("d.merchant_id", [1])
            ->orderBy('d.id','desc')
            ->whereRaw($inCase)
            ->SUM(DB::raw('COALESCE(d.charge + d.cod_charge, 0)'));
        return $query;

    }

    public function getExpenseStatement($request)
    {
        $from = $request->from_date;
        $to = $request->to_date;
        if (isset($request->query_string) && !empty($request->query_string))
        {
            list($from, $to) = explode("~", $request->query_string);
        }
        $dates = getAllDateOfRangePeriod($from, $to);
        foreach ($dates as $key => $date) {
            $single_arr = [];
            $single_arr['expense'] = round($this->Expense($from = $date, $to = $date));
            $single_arr['date'] = $date;
            $results[] = $single_arr;
        }
        return $results;
    }

    public function Expense($from, $to)
    {
        $query = DB::table('expense as e')
            ->Join("expense_category as ec", "e.exp_category_id", "=", "ec.id")
            ->where(["e.status" => 1])
            ->whereBetween('e.expense_date', [$from, $to])
            ->orderBy('e.id','desc')
            ->SUM("e.amount");
        return $query;

    }

    public function getRevenue($request)
    {
        $from = $request->from_date;
        $to = $request->to_date;
        $dates = getAllDateOfRangePeriod($from, $to);
        foreach ($dates as $key => $date) {
            $single_arr = [];
            $single_arr['revenue'] =(float) round($this->Income($from = $date, $to = $date) - $this->Expense($from = $date, $to = $date));
            $single_arr['date'] = $date;
            $results[] = $single_arr;
        }
        return $results;
    }

    public function getFlagStatus()
    {
        // TODO: Implement getFlagStatus() method.
        return DB::table("flag_status")
            ->select("id","flag_text")
            ->whereIn("id", [6,8])
            ->get();
    }
}
