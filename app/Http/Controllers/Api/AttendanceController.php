<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Api\Attendance\AttendanceRepository;
use DB;
use Illuminate\Support\Facades\Input;
use App\DB\Api\SRAttendance;
use Validator;

class AttendanceController extends Controller
{
    protected $_errors;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;
    protected $attendance;

    function __construct(AttendanceRepository $attendance){
        $this->attendance = $attendance;
        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
        date_default_timezone_set('Asia/Dhaka');
    }

    private function getErrorAsString(){
        $errorString ="";
        foreach ($this->_errors as $error) {
            $errorString .= $error.",";
        }
        return $errorString;
    }

    public function dashboard(Request $request){
        $inputs = Input::json()->all();
        if(!$this->validateDashboardRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = $this->error_code;
            $this->response['message']  = $this->invalid_msg;
            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
            return response($this->response, $this->error_code);
        }

        $is_results = $this->attendance->dashboardData($inputs);
        if($is_results){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['response']     = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);
    }

    protected function validateDashboardRequest($inputs){
        $validator = Validator::make($inputs, [
            'sr_id'             => 'required|numeric',
            'company_id'       => 'required|numeric',
//            'fromDate'           => 'required',
//            'toDate'           => 'required',
        ],
        [
            'category_id.between'   => 'In valid category id'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function postAddAttendance(Request $request){
        $inputs = Input::json()->all();
        if(!$this->validateAttendanceRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = $this->error_code;
            $this->response['message']  = $this->invalid_msg;
            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
            return response($this->response, $this->error_code);
        }

        $isAttendanceExist = $this->attendance->isAttendanceExist($inputs);

        if($inputs['is_online'] == 1 && empty($isAttendanceExist)){
            // $id = $this->attendance->postStoreAttendance($inputs);
            $id = $this->attendance->storeWithPolicy($inputs);
            $msg = "Attendance has been successfully added !";
        } else if($inputs['is_online'] == 0 && !empty($isAttendanceExist)) {
            $id = $this->attendance->postUpdateAttendance($inputs, $isAttendanceExist->id);
            $msg = "Attendance has been successfully updated !";
        } else {
            if(empty($isAttendanceExist)){
                $msg = "You did not check in yet, first check in to checkout !";
                $this->response['success']  = true;
                $this->response['code']     = $this->success_code;
                $this->response['data']     = [ 'checked_in' => '00:00', 'checked_out' => '00:00' ];
                $this->response['message']  = $msg;
                return response($this->response, $this->success_code);
            } else {
                $this->attendance->postUpdateOnlyAttendanceStatus($inputs, $isAttendanceExist->id);
                $msg = "Please make a request for checkout !";
                $this->response['success']  = true;
                $this->response['code']     = $this->success_code;
                $this->response['data']     = [
                    'checked_in'  => date('h:i A', strtotime($isAttendanceExist->checkedin_time)),
                    'checked_out' => $isAttendanceExist->checkedout_time != '' ? date('h:i A', strtotime($isAttendanceExist->checkedout_time)) : '00:00' ];
                $this->response['message']  = $msg;
                return response($this->response, $this->success_code);
            }
        }

        if (!empty($id)){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $id;
            $this->response['message']  = $msg;
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't store attendance data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't store attendance data, please check your request format");
        return response($this->response, $this->error_code);
    }

    protected function validateAttendanceRequest($inputs){
        $validator = Validator::make($inputs, [
            'employee_id' => 'required|numeric',
            'company_id'  => 'required|numeric',
            'is_online'   => 'required|numeric',
            'coordinate'   => 'required',
        ],
        [
            'category_id.between'   => 'In valid category id'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    // Only user for testing policy !
    public function postStoreWithPolicy(Request $request){
        $inputs = Input::json()->all();
        if(!$this->validateAttendanceRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = $this->error_code;
            $this->response['message']  = $this->invalid_msg;
            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
            return response($this->response, $this->error_code);
        }

        $isAttendanceExist = $this->attendance->isAttendanceExist($inputs);

        if($inputs['is_online'] == 1 && empty($isAttendanceExist)){
            $id = $this->attendance->storeWithPolicy($inputs);
            $msg = "Attendance has been successfully added !";
        } else if($inputs['is_online'] == 0 && !empty($isAttendanceExist)) {
            $id = $this->attendance->postUpdateAttendance($inputs, $isAttendanceExist->id);
            $msg = "Attendance has been successfully updated !";
        } else {
            if(empty($isAttendanceExist)){
                $msg = "You did not check in yet, first check in to checkout !";
                $this->response['success']  = true;
                $this->response['code']     = $this->success_code;
                $this->response['data']     = [ 'checked_in' => '00:00', 'checked_out' => '00:00' ];
                $this->response['message']  = $msg;
                return response($this->response, $this->success_code);
            } else {
                $msg = "Please make a request for checkout !";
                $this->response['success']  = true;
                $this->response['code']     = $this->success_code;
                $this->response['data']     = [
                    'checked_in'  => date('h:i A', strtotime($isAttendanceExist->checkedin_time)),
                    'checked_out' => $isAttendanceExist->checkedout_time != '' ? date('h:i A', strtotime($isAttendanceExist->checkedout_time)) : '00:00' ];
                $this->response['message']  = $msg;
                return response($this->response, $this->success_code);
            }
        }

        if (!empty($id)){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $id;
            $this->response['message']  = $msg;
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't store attendance data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't store attendance data, please check your request format");
        return response($this->response, $this->error_code);
    }

    public function getAttendanceList(Request $request, $employee_id){
        $inputs['fromDate'] = $request->get('from_date');
        $inputs['toDate'] = $request->get('to_date');
        if($inputs['fromDate'] == '' || $inputs['toDate'] == ''){
            $inputs['fromDate'] = date('Y-m-01');
            $inputs['toDate'] = date('Y-m-d');
        }

        $is_results = $this->attendance->getAttendanceList($inputs, $employee_id);
        if($is_results){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['response']     = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);
    }

    protected function validateAttendanceListRequest($inputs){
        $validator = Validator::make($inputs, [
           'fromDate'   => 'required',
           'toDate'     => 'required',
        ],
        [
            'category_id.between'   => 'In valid category id'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }
}
