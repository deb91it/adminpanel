<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\Agent\AgentRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\AgentAccounts\AgentAccountsRepository;
use App\Repositories\Admin\Driver\DriverRepository;
use DB;
use Excel;


class AgentAccountsController extends Controller
{
    protected $accountsReport;
    protected $agent;

    function __construct(AgentAccountsRepository $accountsReport,
                         AgentRepository $agent) {
        $this->accountsReport = $accountsReport;
        $this->agent = $agent;
    }

    public function getReport(Request $request){
        return view('admin.accounts.agent.index');
    }

    public function getDataTableReport(Request $request){
        return $this->accountsReport->getReportPaginated($request);
    }

    public function getAgentTransactionLogs(Request $request, $agent_id){
        return view('admin.accounts.agent.transaction-log')
            ->withAgent($this->getAgentDetails($agent_id))
            ->withAgentLists($this->agent->getAgentList());
    }

    private function getAgentDetails($agent_id) {
        return DB::table('agents as a')
            ->selectRaw("
                a.id,
                a.first_name,
                a.last_name,
                m.mobile_no,
                m.email
            ")
            ->leftJoin('members as m', 'm.id', '=', 'a.member_id')
            ->where([ 'a.id' => $agent_id ])
            ->first();
    }

    public function postAgentTransactionLogs(Request $request){
        $agent_id = $request->get('agent_id');
        return $this->accountsReport->agentTransactionLogs($request, $agent_id);
    }

    public function postAgentSummaryData(Request $request){
        $this->response['success'] = false;
        $agent_id = $request['agent_id'];
        if ($agent_id != '') {
            $agent = $this->getAgentDetails($agent_id);

            if (!empty($agent)) {
                $this->response['success'] = true;
                $this->response['agent'] = $agent;
            }
        }
        return response($this->response, 200);
    }












    public function postExportFile(Request $request)
    {
        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-merchant-accounts-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-merchant-accounts-' . $start_date . '-To-' . $end_date;
        }

        // $data = [ 'Nmae' => "Mamun"];
        $data = $this->accountsReport->getExportFileData($request);
        if (empty($data)) {
            $this->response['success'] = false;
            $this->response['msg']  = "Didn't found any data !";
            return response($this->response,200);
        }

        return Excel::create($file_name, function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->store($export_type, 'exports/', true);
    }

    public function reportDetails(Request $request, $week_id){
        $data  = $this->accountsReport->getReportDetails($request, $week_id);
        return view('admin.accounts.details')
            ->withMerchant($data['merchants'])
            ->withTrips($data['trips'])
            ->withWeekid($data['week_id'])
            ->withStatus($data['settle_status']);
    }

    public function doneWeekSettle(Request $request)
    {
        $week_id = $request['week_id'];
        if ($week_id != '' && is_numeric($week_id)) {
            $week_data = DB::table('week_settles')
                ->select('id', 'trip_ids')
                ->where('id', $week_id)
                ->first();

            if (! empty($week_data)) {
                DB::table('week_settles')
                    ->where('id', $week_id)
                    ->update(['settle_status' => 1]);

                if ($week_data->trip_ids != '') {
                    $trip_ids = explode(",", $week_data->trip_ids);

                    DB::table('trips')
                        ->whereIn('id', $trip_ids)
                        ->update(['settle_status' => 1]);

                }

                return response(['success' => true, 'msg' => 'Successfully settle down the week'], 200);
            }
        }

        return response(['success' => false, 'msg' => 'Could not settle down the week'], 200);
    }

    public function anotherReport(Request $request){
        return view('admin.accounts.new_index');
    }

    public function getAnotherReportData(Request $request){
        return $this->accountsReport->getAnotherReportData($request);
    }

    private function getMerchantDetails($merchant_id) {
        return DB::table('merchants as merc')
            ->selectRaw('
                merc.id, 
                merc.first_name, 
                merc.last_name, 
                m.mobile_no, 
                m.email,
                SUM(mc.no_of_trip) as  no_of_trip,
                SUM(mc.balance) as  balance,
                SUM(mc.total_paid) as  total_pay,
                SUM(mc.total_receive) as  total_receive
            ')
            ->join('members as m', 'm.id', '=', 'merc.member_id')
            ->join('merchant_accounts as mc', 'mc.merchant_id', '=', 'merc.id')
            ->where([ 'merc.id' => $merchant_id ])
            ->first();
    }


    private function getDriverBasedOnMerchant($merchant_id, &$key_amounts) {
        //$key_amounts['total_balance'] = 0;
        //$key_amounts['total_trip'] =  0;
        $key_amounts['total_paid'] =  0;
        $key_amounts['total_receive'] =  0;

        $drivers = DB::select("SELECT 
                    d.id,
                    d.id as driver_id,
                    CONCAT(d.first_name, ' ', d.last_name, ' ', COALESCE(m.mobile_no,''), ' ', COALESCE(m.email,'')) AS driver,
                    mc.no_of_trip,
                    mc.balance as current_balance,
                    mc.total_paid,
                    mc.total_receive
                FROM drivers as d 
                LEFT JOIN members as m ON m.id = d.member_id
                JOIN merchant_accounts as mc ON mc.driver_id = d.id
                WHERE mc.merchant_id = {$merchant_id}");

        if (! empty($drivers)) {
            foreach ($drivers as $key => $val) {
                //$key_amounts['total_balance'] += $val->current_balance;
                //$key_amounts['total_trip'] += $val->no_of_trip;
               // $drivers[$key]->current_balance = (( $val->trip_balance - $val->total_receive) + $val->total_paid ) ;
                $key_amounts['total_paid'] += $val->total_paid;
                $key_amounts['total_receive'] += $val->total_receive;
            }
        }

        return $drivers;
    }

    public function postPayByEzzyrAccounts(Request $request){
        $key_amounts = [];
        $this->response['success'] = false;
        $this->response['message'] = 'Unable to done transaction !';

        $merchant_id = $request->get('merchant_id');
        $transaction_type = $request->get('transaction_type');
        $type = ($transaction_type == 'pay') ? '1' : '0' ;
        $operator = ($transaction_type == 'pay') ? '+' : '-' ;
        $db_field = ($transaction_type == 'pay') ? 'total_paid' : 'total_receive' ;
        $transaction_key = "ezzyr_{$transaction_type}";

        if (!empty($merchant_id)) {
            $driver_id = $request->get('driver_id');
            $due_amount_arr = $request->get('due_amount');
            $paid_amount_arr = $request->get('paid_amount');
            $total_amount = 0;

            for ($i = 0; $i < count($driver_id);  $i++) {
                if (empty($driver_id[$i]) || empty($due_amount_arr[$i]) || empty($paid_amount_arr[$i])) {
                    continue;
                }

                $due_amount = abs($due_amount_arr[$i]);
                $paid_amount = abs($paid_amount_arr[$i]);
                if ($paid_amount > $due_amount) {
                    $paid_amount = $due_amount;
                }

              //  $total_amount += "{$operator}{$paid_amount}" + 0;

                //Update driver transaction table
                if ($paid_amount > 0) {
                    $sql = "INSERT INTO driver_transaction_logs (
                            driver_id,
                            merchant_id,
                            transaction_key,
                            previous_balance,
                            amount,
                            transaction_type,
                            created_at
                        ) SELECT '{$driver_id[$i]}', '{$merchant_id}', '{$transaction_key}', balance, '{$operator}{$paid_amount}', '{$type}', '" . date('Y-m-d h:i:s') . "' 
                            FROM merchant_accounts as mc
                          WHERE 
                              mc.driver_id = $driver_id[$i] 
                                AND 
                              mc.merchant_id = {$merchant_id}";
                    DB::statement($sql);

                    //Update merchant accounts table
                    DB::statement("UPDATE merchant_accounts SET balance = balance + {$operator}{$paid_amount}, {$db_field} = {$db_field} + {$paid_amount}  WHERE driver_id = {$driver_id[$i]} AND merchant_id = {$merchant_id}");
                    $paid_amount = 0;
                }
            }

            $this->response['success'] = true;
            $this->response['message'] = 'Successfully done transaction !';
        }

        $merchants = $this->getMerchantDetails($merchant_id);
        $key_amounts['total_balance'] = $merchants->balance;
        $key_amounts['total_trip'] = $merchants->no_of_trip;
        $this->response['merchant'] = $merchants;
        $this->response['drivers'] = $this->getDriverBasedOnMerchant($merchant_id, $key_amounts);
        $this->response['key_amount'] = $key_amounts;
        return response($this->response, 200);

    }

    public function postMerchantTransactionLogsByDate(Request $request){
        $this->response['success'] = false;
        $this->response['data'] = [];
        $this->response['message'] = 'Did not found data !';

        $merchant_id = $request->get('merchant_id');
        $transaction_date = $request->get('transaction_date');

        $this->response['others']['date'] = $transaction_date;

        $logs =  DB::table('driver_transaction_logs as log')
            ->selectRaw("log.id,
                log.created_at AS daaatee,
                log.amount,
                log.driver_id AS d_id,
                log.merchant_id,
                log.transaction_key,
                log.previous_balance,
                (log.previous_balance + log.amount) as balance,
                CONCAT(d.first_name, ' ', d.last_name, '<br>', COALESCE(m.mobile_no,'')) AS driver,
                DATE_FORMAT(log.created_at, '%h:%i %p') AS format_time")
            ->join('drivers as d', 'd.id', '=', 'log.driver_id')
            ->leftJoin('members as m', 'm.id', '=', 'd.member_id')
            ->where([ 'log.merchant_id' => $merchant_id ])
            ->whereRaw("( log.transaction_key = 'ezzyr_pay' OR log.transaction_key = 'ezzyr_receive' )")
            ->where(function ($query) use ($transaction_date) {
                $query->whereBetween('log.created_at', [$transaction_date . " 00:00:00", $transaction_date . " 23:59:59"]);
            })
            ->orderBY('log.id', 'asc')
            ->get();

        if (! empty($logs)) {
            $this->response['success'] = true;
            $this->response['data'] = $logs;
            $this->response['message'] = 'Data available !';
        }

        return response($this->response, 200);
    }

    public function getViewTableView(Request $request){
        return view('admin.accounts.view-table');
    }

    public function postViewTableView(Request $request){
        return $this->accountsReport->getViewAccounts($request);
    }
}