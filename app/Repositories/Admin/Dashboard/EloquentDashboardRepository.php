<?php namespace App\Repositories\Admin\Dashboard;

use App\DB\Notification;
use DB;
use NumberFormatter;
use App\Repositories\Admin\Expense\ExpenseRepository;
use App\Repositories\Admin\FinancialStatement\FinancialStatementRepository;

class EloquentDashboardRepository implements DashboardRepository
{
    protected $expense;
    protected $report;

    function __construct(
        ExpenseRepository $expense,
        FinancialStatementRepository $report
    )
    {
        $this->expense = $expense;
        $this->report = $report;
    }

    public function getDashboardStatistics()
    {
        $current_date = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-1 day"));
        $year = date('Y-01-01');
        $month = date('Y-m-01');
        $ext_year = "WHERE merchant_id NOT IN (1) AND created_at BETWEEN '{$year} 00:00:01' AND '{$current_date} 23:59:59'";
        $ext_month = "WHERE merchant_id NOT IN (1) AND created_at BETWEEN '{$month} 00:00:01' AND '{$current_date} 23:59:59'";
        $ext_day = "WHERE merchant_id NOT IN (1) AND created_at BETWEEN '{$current_date} 00:00:01' AND '{$current_date} 23:59:59'";
        $ext_yesterday = "WHERE merchant_id NOT IN (1) AND created_at BETWEEN '{$yesterday} 00:00:01' AND '{$yesterday} 23:59:59'";
        return DB::table("merchants as m")
            ->select(
                DB::raw("COALESCE(COUNT(m.id), 0) as number_of_merchant"),
                DB::raw("(Select COALESCE(COUNT(id), 0) from stores) as number_of_stores"),
                DB::raw("(Select COALESCE(COUNT(id), 0) from products) as number_of_products"),
                DB::raw("(Select COALESCE(COUNT(id), 0) from hub where status = 1) as number_of_hub"),
                DB::raw("(Select COALESCE(COUNT(id), 0) from deliveries {$ext_year}) as number_of_parcel_of_this_year"),
                DB::raw("(Select COALESCE(COUNT(id), 0) from deliveries {$ext_month}) as number_of_parcel_of_this_month"),
                DB::raw("(Select COALESCE(COUNT(id), 0) from deliveries {$ext_day}) as number_of_parcel_of_this_day"),
                DB::raw("(Select COALESCE(COUNT(id), 0) from deliveries {$ext_yesterday}) as number_of_parcel_of_yesterday"),
                DB::raw("(Select COALESCE(SUM(amount), 0) from invoices {$ext_year}) as payment_of_merchant_of_this_year"),
                DB::raw("(Select COALESCE(SUM(amount), 0) from invoices {$ext_month}) as payment_of_merchant_of_this_month"),
                DB::raw("(Select COALESCE(SUM(amount), 0) from invoices {$ext_day}) as payment_of_merchant_of_this_day"),
                DB::raw("(Select COALESCE(SUM(amount), 0) from invoices {$ext_yesterday}) as payment_of_merchant_of_yesterday")
        )->first();
    }

    public function getOperationStatistics($request)
    {
        $ext_query = '';
        $del_ext_query = '';
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $param = $request->param;

        if ($param == "today"){
            $from = date('Y-m-d');
            $to = date('Y-m-d');
        }elseif ($param == "yesterday"){
            $from = date('Y-m-d', strtotime("-1 day"));
            $to = date('Y-m-d', strtotime("-1 day"));
        }elseif ($param == "month"){
            $from = date('Y-m-01');
            $to = date('Y-m-d');
        }else{
            $from = date('Y-01-01');
            $to = date('Y-m-d');
        }

        $ext_query = " AND merchant_id NOT IN (1) AND created_at between '{$from} 00:00:01' AND '{$to} 23:59:59'";
        $del_ext_query = " WHERE merchant_id NOT IN (1) AND  created_at between '{$from} 00:00:01' AND '{$to} 23:59:59'";

        $query = DB::table("deliveries as d")
            ->select(
                DB::raw("(SELECT COALESCE(COUNT(id), 0) FROM deliveries {$del_ext_query}) AS number_of_parcel"),
                DB::raw("(SELECT COALESCE(COUNT(id)) FROM deliveries where status = 6 {$ext_query}) AS number_of_parcel_delivered"),
                DB::raw("(SELECT COALESCE(COUNT(id)) FROM deliveries where status = 9 {$ext_query}) AS number_of_parcel_hold"),
                DB::raw("(SELECT COALESCE(COUNT(id)) FROM deliveries where status = 8 {$ext_query}) AS number_of_parcel_returned"),
                DB::raw("(SELECT COALESCE(COUNT(id)) FROM deliveries where status = 5 {$ext_query}) AS number_of_parcel_transit")
        );
        $query = $query->first();
        $query->number_in_word = ucfirst($f->format($query->number_of_parcel));
        return $query;
    }

    public function getFinancialStatistics($request)
    {
        $data = [];

        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $param = $request->param;

        if ($param == "monthly"){
            $from = date('Y-m-01');
            $to = date('Y-m-d');
            $request->from_date = $from;
            $request->to_date = $to;
            $income = $this->report->getIncomeStatement($request);
            $expense = $this->report->getExpenseStatement($request);
            $revenue = $this->report->getRevenue($request);
            $dates = getAllDateOfRangePeriod($from, $to);
            foreach ($dates as $v)
            {
                $labels = date("M j", strtotime($v));
                $results[] = $labels;
                $all_dates = date("Y-m-d", strtotime($v));
                $dates[] = $all_dates;
            }
        }else{
            foreach ($this->getMonthOfYear() as $key => $y){
                list($from, $to) = explode("~", $y);
                $income[]  = (float) round($this->report->Income($from, $to));
                $expense[] = (float) round($this->report->Expense($from, $to));
                $revenue[] = (float) round($this->report->Income($from, $to) - $this->report->Expense($from, $to));
                $results = $this->listOfMonth();
                $dates = $this->getMonthOfYear();
            }
        }
        $data = [
            'income' => $income,
            'expense' => $expense,
            'revenue' => $revenue,
            'type' => ucfirst($param),
            'labels' => $results,
            'dates' => $dates,
        ];
        return $data;
    }

    private function listOfMonth()
    {
        return [
            "Jan", "Feb", "Mar", "Apr", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
    }

    private function getMonthOfYear()
    {
        $year = date("Y");
        $month = date("m");
        $list = [];
        for ($i = 1; $i <= $month; $i++)
        {
            $list[] = $year."-".$i."-01"."~".$year."-".$i."-".date('t', strtotime($year."-".$i."-01"));
        }
        return $list;
    }

    public function getCollectionStatistics($request)
    {
        $single_arr = [];
        $ext_query = '';
        $param = $request->param;

        if ($param == "today"){
            $from = date('Y-m-d');
            $to = date('Y-m-d');
        }elseif ($param == "yesterday"){
            $from = date('Y-m-d', strtotime("-1 day"));
            $to = date('Y-m-d', strtotime("-1 day"));
        }elseif ($param == "month"){
            $from = date('Y-m-01');
            $to = date('Y-m-d');
        }else{
            $from = date('Y-01-01');
            $to = date('Y-m-d');
        }

        if (!empty($from) && !empty($to))
        {
            $inCase = "
             CASE
                WHEN d.status = 6 THEN d.delivery_date
            ELSE d.return_date
            END  between '{$from}' AND '{$to}'";
            $ext_query = "{$inCase} AND merchant_id NOT IN (1) AND";
        }

        $query = DB::table("hub as h")
            ->select("h.id","h.hub_name",
                DB::raw("(SELECT 
                            COALESCE(SUM(d.receive_amount), 0)
                        FROM
                            deliveries AS d
                        WHERE
                        {$ext_query} 
                            d.hub_id = h.id
                                AND d.status IN (6, 7, 8)) AS hub_amount")
                )
        ->where(["h.status" => 1])
        ->get();

        $total_amount = "(SELECT 
                            COALESCE(SUM(d.receive_amount), 0) AS total_amount
                        FROM
                            deliveries AS d
                        WHERE
                        {$ext_query}  
                            d.status IN (6, 7, 8))";
        $total_amount = DB::select($total_amount);
        $single_arr['hub'] = $query;
        $single_arr['total_amount'] = $total_amount;

        return $single_arr;

    }

}
