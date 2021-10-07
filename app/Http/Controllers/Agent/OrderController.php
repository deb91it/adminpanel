<?php

namespace App\Http\Controllers\Agent;

use App\DB\Agent\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\Order\OrderRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use Excel;
use DB;
use Validator;

/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $agent;
    protected $order_list;
    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;
    protected $agent_id;
    protected $company_id;

    /**
     * AgentController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(

         OrderRepository $order_list
        , RoleRepository $roles
        , MemberRepository $member
    )
    {

        $this->order_list = $order_list;
        $this->roles = $roles;
        $this->member = $member;
        $this->agent_id = getLogedinAgentId();
        $this->company_id = get_agent_company_id();
    }

    public function index(Request $request)
    {
        $from = '';
        $to = '';
        $type = '';
        if (!empty($request->from)){
            $from =$request->from;
        }
        if (!empty($request->to)){
            $to =$request->to;
        }
        if (!empty($request->search_for)){
            $type =$request->search_for;
        }
        $per_page = 25;
        return view('agent.order.index',compact('company_roles','type','from','to'))
        ->withAgents($this->order_list->getReportPaginated($request, $per_page, $this->company_id,$from,$to,$type));
    }

    public function getDataTableReport(Request $request){
        $from = $request->from_date;
        $to = $request->to_date;
        $type = '';
        if (!empty($request->search_for)){
            $type =$request->search_for;
        }
        $per_page = 25;
        return $this->order_list->getReportPaginated($request,$per_page, $this->company_id,$from,$to,$type);
    }



    public function create()
    {
        $heads = orderPolicyHead::where('company_id',$this->company_id)->pluck('name','id')->toArray();
        return view('agent.order.create',compact('heads'));
    }

    public function store(orderPolicyRequest $request)
    {
        $order_list = $this->order_list->store($request, $this->agent_id);
        if ($order_list > 0) {
            return redirect('order-list/create')->with('flashMessageSuccess','The order list has successfully created !');
        }
        return redirect('order-list/create')->with('flashMessageError','Unable to create order list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function remarks(Request $request)
    {
        $remark = Order::select('remarks')->find($request->order_id);
        return $remark;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateorderManually(Request $request)
    {
        $check = order::find($request->row_id);
        if (!empty($check)) {
            $updateData = $this->order_list->updateorderManually($request);
            if ($updateData > 0) {
                return redirect('order-list')->with('flashMessageSuccess', 'order successfully updated.');
            }
            return redirect('order-list')->with('flashMessageError', 'Unable to update order.');
        }
        $storeData = $this->order_list->saveorderManually($request);
        if ($storeData > 0) {
            return redirect('order-list')->with('flashMessageSuccess', 'order successfully saved.');
        }
        return redirect('order-list')->with('flashMessageSuccess', 'Unable to save order.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function vieworderDetails($user_id){
        $from = date('Y-m-01');
        $to = date('Y-m-d');
        $process_result = [];
        $results = [];
        $dates = getAllDateOfRangePeriod($from, $to);
        $details = $this->order_list->vieworderDetails($user_id,$from,$to);
        if (!empty($details)) {
            foreach ($details as $val) {
                $process_result[$val->order_date] = [
                    'in_time' => $val->c_time,
                    'out_time' => $val->o_time,
                    'status' => $val->current_status
                ];
            }
        }
        foreach ($dates as $key => $date) {
            $single_arr = [];
            if (array_key_exists($date, $process_result)) {
                $single_arr['in_time'] = $process_result[$date]['in_time'];
                $single_arr['out_time'] = $process_result[$date]['out_time'];
                $single_arr['status'] = $process_result[$date]['status'];
                $single_arr['date'] = $date;
            } else {
                $single_arr['in_time'] = '-';
                $single_arr['out_time'] = '-';
                $single_arr['status'] = 'Absent';
                $single_arr['date'] = $date;
            }
            $results[] = $single_arr;
        }
        $data['result'] = $results;
        $data['user_id'] = $user_id;
        return $data;
    }

    public function searchorderDetails(Request $request){

        $user_id = $request->user_id;
        $month = $request->month;
        if ($month > date('m')){
            $data['result'] = 0;
            $data['user_id'] = $user_id;
            return $data;
        }
        $number = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
        $from = date('Y-'.$month.'-01');
        $todate = $month >= date('m') ? date('Y-'.$month.'-d') : date('Y-'.$month.'-'.$number);
        $to = $todate;
        $process_result = [];
        $results = [];
        $dates = getAllDateOfRangePeriod($from, $to);
        $details = $this->order_list->vieworderDetails($user_id,$from,$to);
        if (!empty($details)) {
            foreach ($details as $val) {
                $process_result[$val->order_date] = [
                    'in_time' => $val->c_time,
                    'out_time' => $val->o_time,
                    'status' => $val->current_status
                ];
            }
        }
        foreach ($dates as $key => $date) {
            $single_arr = [];
            if (array_key_exists($date, $process_result)) {
                $single_arr['in_time'] = $process_result[$date]['in_time'];
                $single_arr['out_time'] = $process_result[$date]['out_time'];
                $single_arr['status'] = $process_result[$date]['status'];
                $single_arr['date'] = $date;
            } else {
                $single_arr['in_time'] = '-';
                $single_arr['out_time'] = '-';
                $single_arr['status'] = 'Absent';
                $single_arr['date'] = $date;
            }
            $results[] = $single_arr;
        }
        $data['result'] = $results;
        $data['user_id'] = $user_id;
        return $data;
    }


    public function checkAgent($agent_id){
        if ($agent_id != getLogedinAgentId()){
            return redirect('order-list')->with('flashMessageError','Invalid action');
        }
        return true;
    }

    public function postExportFile(Request $request)
    {
        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-agent-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-agent-from-' . $start_date . '-To-' . $end_date;
        }
        // $data = [ 'Nmae' => "Mamun"];
        $data = (array) $this->order_list->exportFile($request);
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
    

}
