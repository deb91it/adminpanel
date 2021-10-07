<?php namespace App\Repositories\Admin\AgentAccounts;
 use DB;
 use Datatables;
 use DateTime;

class EloquentAgentAccountsRepository implements AgentAccountsRepository
{
    function __construct() {

    }

    public function getReportPaginated($request)
    {
        $start_date = '';
        $end_date = '';
        $date_range = $request->get('columns')[12]['search']['value'];
        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (!date_validate($start_date)  || ! date_validate($end_date)) {
                $start_date = '';
                $end_date = '';
            }
        }

        $query = DB::table('agents as ag')
            ->select(
                DB::raw("CONCAT(ag.first_name, ' ', ag.last_name, '<br>',  mem.mobile_no) AS agent"),
                'aac.*'
            )
            ->join('members as mem', 'mem.id', '=', 'ag.member_id')
            ->join(DB::raw('(
                            SELECT
                                agent_id,
                                agent_id as _id,
                                SUM(no_of_trip) AS no_of_trip,
                                SUM(total_run) AS total_run,
                                SUM(total_fare) AS total_fare,
                                SUM(total_discount) AS total_discount,
                                SUM(promo_discount) AS promo_discount,
                                SUM(referral_discount) AS referral_discount,
                                SUM(credit_discount) AS credit_discount,
                                SUM(fraction_discount) AS fraction_discount,
                                SUM(balance) AS balance,                             
                                agent_id AS ezzyr_commission,
                                (SELECT coalesce(SUM(amount), 0) as rec FROM agent_transaction_logs WHERE agent_id = _id) AS total_receive           
                            FROM `merchant_accounts` WHERE agent_id > 0 GROUP BY agent_id
                        ) AS aac'), function($join) {
                            $join->on('aac.agent_id', '=', 'ag.id');
                        });

        return Datatables::of($query)
            ->addColumn('action', function ($agent) {
                return '
                    <div class="btn-group">
                        <button type="button" class="btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                        </button>
                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                        <div class="dropdown-menu">
                            <a href="#" class="receive-from-agent dropdown-item m--font-primary" data-toggle="modal" data-target="#receive-from-agent" value="' . $agent->agent_id . '" name="receive">
                                <i class="m-nav__link-icon fa fa-cc-mastercard"></i>Receive
                            </a>
                            <a href="' . route("admin.accounts.agent.transaction.log", $agent->agent_id) . '" class="dropdown-item m--font-primary" >
                                <i class="m-nav__link-icon fa fa-cc-mastercard"></i>Logs
                            </a>
                            <div class="dropdown-divider"></div>
                        </div>
                    </div>';
            })
            ->filterColumn('agent', function($query, $keyword) {
                $query->whereRaw("CONCAT(ag.first_name, ag.last_name, mem.mobile_no, mem.email) like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getExportFileData($request)
    {
        $date_between = '';
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        // Set last one month date range if not selected
        if ($start_date == '' || $end_date == '') {
            $start_date = date('Y-m-d', strtotime('today - 30 days'));
            $end_date = date('Y-m-d');
        }


        //Date range is more than 31 days then return false !
        if ( date_diff(date_create($start_date), date_create($end_date))->format("%a") > 31) {
            $start_date = date('Y-m-d', strtotime($end_date . ' -31 days'));
        }

        $start_date = trim($start_date) . " 00:00:00";
        $end_date = trim($end_date) . " 23:59:59";

        if ($start_date != '' && $end_date != '') {
            $date_between = "WHERE checkout_date between '{$start_date}' AND '{$end_date}'";
        }

        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $data = DB::table('merchants as m')
            ->selectRaw(
                "CONCAT(m.first_name, ' ', m.last_name, ' ',  mem.mobile_no) AS Merchant,
                mc.no_of_trip AS No_of_trip,
                COALESCE(mc.total_run, 0) AS Total_run,
                COALESCE(mc.total_fare, 0) AS Total_fare,
                COALESCE(mc.ezzyr_commission, 0) AS Ezzyr_commission,
                COALESCE(mc.total_discount, 0) AS Total_discount,
                COALESCE(mc.promo_discount, 0) AS Promo_discount,
                COALESCE(mc.referral_discount, 0) AS Referral_discount,
                COALESCE(mc.credit_discount, 0) AS Credit_discount,
                COALESCE(mc.fraction_discount, 0) AS Fraction_discount,
                COALESCE(mc.driver_receive, 0) AS Driver_receive,
                COALESCE(mc.balance, 0) AS Balance,
                COALESCE(mc.total_paid, 0) AS Total_paid,
                COALESCE(mc.total_receive, 0) AS Total_receive,
                CONCAT(agn.first_name, ' ', agn.last_name) AS Agent")
            ->join('members as mem', 'mem.id', '=', 'm.member_id')
            ->leftJoin('agents as agn', 'agn.id', '=', 'm.agent_id')
            ->join(DB::raw('(
                            SELECT
                                merchant_id,
                                SUM(no_of_trip) AS no_of_trip,
                                SUM(total_run) AS total_run,
                                SUM(total_fare) AS total_fare,
                                SUM(ezzyr_commission) AS ezzyr_commission,
                                SUM(total_discount) AS total_discount,
                                SUM(promo_discount) AS promo_discount,
                                SUM(referral_discount) AS referral_discount,
                                SUM(credit_discount) AS credit_discount,
                                SUM(fraction_discount) AS fraction_discount,
                                SUM(driver_receive) AS driver_receive,
                                SUM(balance) AS balance,
                                SUM(total_paid) AS total_paid,
                                SUM(total_receive) AS total_receive          
                            FROM `merchant_accounts` GROUP BY merchant_id
                        ) AS mc'), function($join) {
                $join->on('mc.merchant_id', '=', 'm.id');
            })->orderBy('m.id', 'asc')
            ->get();
        return $data;
    }

    public function getReportDetails($request, $week_id)
    {
        $response = ['trips' => '', 'merchants' => '', 'week_id' => $week_id, 'status' => ''];
        $date_range = DB::table('week_settles as ws')
            ->select(
                'ws.week_started_at','ws.week_end_at','ws.status','ws.settle_status','ws.trip_ids',
                DB::raw('CONCAT(mrc.first_name, " ", mrc.last_name) AS name'),
                'mrc.is_get_commission', 'm.email', 'm.mobile_no'
            )->join('merchants as mrc', 'mrc.id', '=', 'ws.merchant_id')
            ->join('members as m', 'm.id', '=', 'mrc.member_id')
            ->where(['ws.id' => $week_id])
            ->first();

        if (! empty($date_range)) {
            $response['merchants'] = $date_range;
            $trip_ids = explode(",", $date_range->trip_ids);

            $trips = DB::table('trips as t')
                ->select(
                    't.*',
                    'p.id as passenger_id', 'p.first_name as p_first_name', 'p.last_name as p_last_name',
                    'd.id as driver_id', 'd.first_name as d_first_name', 'd.first_name as d_last_name',
                    'mem.mobile_no as d_mobile_no',
                    'pm.name as payment_method',
                    'v.id as vehicle_id', 'v.model', 'v.make_year', 'v.license_plate', 'v.color', 'v.category_id',
                    'vc.type_name'
                )
                ->join('drivers as d', 'd.id', '=', 't.driver_id')
                ->join('members as mem', 'mem.id', '=', 'd.member_id')
                ->join('passengers as p', 'p.id', '=', 't.passenger_id')
                ->join('payment_methods as pm', 'pm.id', '=', 't.payment_method_id')
                ->join('vehicles as v', 'v.id', '=', 't.vehicle_id')
                ->join('vehicle_categories as vc', 'vc.id', '=', 'v.category_id')

               // ->whereBetween('t.checkout_at', [$date_range->week_started_at, $date_range->week_end_at])
                ->whereIn('t.id', $trip_ids)
                ->orderBy('t.id', 'desc')
                ->get();

            $response['trips'] = $trips ;
            $response['week_id'] = $week_id;
            $response['settle_status'] = $date_range->settle_status;
        }

        return $response;
    }

    public function agentTransactionLogs($request, $agent_id)
    {
        $start_date = ''; $end_date = '';
        // Get selected date range
        $date_range = (isset($request->get('columns')[6]['search']['value'])) ? $request->get('columns')[6]['search']['value'] : '';

        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (!date_validate(date('Y-m-d', strtotime($start_date)))  || ! date_validate(date('Y-m-d', strtotime($end_date)))) {
                $start_date = '';
                $end_date = '';
            }
        }

        if ($start_date != '' && $end_date != '') {
            $start_date = trim($start_date) . " 00:00:00";
            $end_date = trim($end_date) . " 23:59:59";
        }

        $query = DB::table('agent_transaction_logs')
            ->selectRaw('
                id, 
                agent_id, 
                previous_balance, 
                amount, 
                current_balance,
                rpk, 
                previous_km, 
                paid_km, 
                current_km, 
                status, 
                created_at
            ')
            ->where([ 'agent_id' => $agent_id ]);

        return Datatables::of($query)
            ->make(true);
    }

}