<?php

namespace App\Http\Controllers\Agent;

use App\DB\Agent\AttendancePolicy;
use App\DB\Agent\AttendancePolicyHead;
use App\DB\Api\Attendance;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\AttendanceList\AttendanceListRepository;
use App\Repositories\Agent\Agent\AgentRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Http\Requests\Agent\AttendancePolicyRequest;
use Excel;
use DB;
use Validator;
use Illuminate\Support\Facades\Hash;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class AttendanceListController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $agent;
    protected $attendance_list;

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
        AgentRepository $agent
        , AttendanceListRepository $attendance_list
        , RoleRepository $roles
        , MemberRepository $member
    )
    {
        $this->agent = $agent;
        $this->attendance_list = $attendance_list;
        $this->roles = $roles;
        $this->member = $member;
        $this->agent_id = getLogedinAgentId();
        $this->company_id = get_agent_company_id();
    }

    public function index(Request $request)
    {
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        $per_page = 25;
        $flags = [
            'P' => 'Present',
            'A' => 'Absent',
            'L' => 'Leave',
            'H' => 'Holiday',
            'W' => 'Weekend',
            'D' => 'Delay',
            'E' => 'Ext delay'
        ];
        $company_roles = DB::table('roles')->where('company_id',get_agent_company_id())->get();
        return view('agent.attendance-list.index',compact('company_roles','flags'))
        ->withAgents($this->attendance_list->getReportPaginated($request, $per_page, $this->company_id,$from,$to));
    }

    public function getDataTableReport(Request $request){
        $from = $request->from_date;
        $to = $request->to_date;
        $per_page = 25;
        return $this->attendance_list->getReportPaginated($request,$per_page, $this->company_id,$from,$to);
    }



    public function create()
    {
        $heads = AttendancePolicyHead::where('company_id',$this->company_id)->pluck('name','id')->toArray();
        return view('agent.attendance-list.create',compact('heads'));
    }

    public function store(AttendancePolicyRequest $request)
    {
        $attendance_list = $this->attendance_list->store($request, $this->agent_id);
        if ($attendance_list > 0) {
            return redirect('attendance-list/create')->with('flashMessageSuccess','The attendance list has successfully created !');
        }
        return redirect('attendance-list/create')->with('flashMessageError','Unable to create attendance list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
        $resp = [];
        $resp['success'] = true;
        $exists = $this->attendance_list->getEmployeeAttendanceDetails($request);
        if (empty($exists)){
            $resp['success'] = false;
            $resp ['response'] = ["user_id"=>$request->user_id];
            return ($resp);
        }
        $resp ['response'] = $exists;
        return ($resp);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAttendanceManually(Request $request)
    {
        $check = Attendance::find($request->row_id);
        if (!empty($check)) {
            $updateData = $this->attendance_list->updateAttendanceManually($request);
            if ($updateData > 0) {
                return redirect('attendance-list')->with('flashMessageSuccess', 'attendance successfully updated.');
            }
            return redirect('attendance-list')->with('flashMessageError', 'Unable to update attendance.');
        }
        $storeData = $this->attendance_list->saveAttendanceManually($request);
        if ($storeData > 0) {
            return redirect('attendance-list')->with('flashMessageSuccess', 'attendance successfully saved.');
        }
        return redirect('attendance-list')->with('flashMessageSuccess', 'Unable to save attendance.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewAttendanceDetails($user_id){
        $from = date('Y-m-01');
        $to = date('Y-m-d');
        $process_result = [];
        $results = [];
        $dates = getAllDateOfRangePeriod($from, $to);
        $details = $this->attendance_list->viewAttendanceDetails($user_id,$from,$to);
        if (!empty($details)) {
            foreach ($details as $val) {
                $process_result[$val->attendance_date] = [
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

    public function searchAttendanceDetails(Request $request){

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
        $details = $this->attendance_list->viewAttendanceDetails($user_id,$from,$to);
        if (!empty($details)) {
            foreach ($details as $val) {
                $process_result[$val->attendance_date] = [
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
            return redirect('attendance-list')->with('flashMessageError','Invalid action');
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
        $data = (array) $this->attendance_list->exportFile($request);
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
