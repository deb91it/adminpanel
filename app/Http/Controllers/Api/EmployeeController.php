<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Api\Employee\EmployeeRepository;
use DB;
use Illuminate\Support\Facades\Input;
use Validator;

class EmployeeController extends Controller
{
    protected $_errors;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;
    protected $employee;

    function __construct(EmployeeRepository $employee){
        $this->employee = $employee;
        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
    }

    public function getSrDashboardData($sr_id){
        $inputs = Input::json()->all();
        /*if(!$this->validateDashboardRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = $this->error_code;
            $this->response['message']  = $this->invalid_msg;
            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
            return response($this->response, $this->error_code);
        }*/
        $is_results = $this->employee->getSrDashboardData($inputs, $sr_id);
        if(! empty($is_results)){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['data']     = [];
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);
    }

    public function getSrMeetingData(Request $request, $sr_id){
        $is_results = $this->employee->getSrMeetingData($request, $sr_id);
        if(! empty($is_results)){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $is_results;
            $this->response['message']  = "Data is available !";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['data']     = [];
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);
    }

    public function getSrOutletOwnerList(Request $request, $sr_id){
        $is_results = $this->employee->getSrOutletOwnerList($request, $sr_id);
        if(! empty($is_results)){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['data']     = [];
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);
    }

    private function getErrorAsString(){
        $errorString ="";
        foreach ($this->_errors as $error) {
            $errorString .= $error.",";
        }
        return $errorString;
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

    public function getTseDashboardData($tse_id){
        $inputs = Input::json()->all();
        /*if(!$this->validateTseDashboardRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = $this->error_code;
            $this->response['message']  = $this->invalid_msg;
            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
            return response($this->response, $this->error_code);
        }*/

        $is_results = $this->employee->getTseDashboardData($inputs, $tse_id);
        if($is_results){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data'] = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);
    }

    protected function validateTseDashboardRequest($inputs)
    {
        $validator = Validator::make($inputs, [
            'tse_id' => 'required|numeric',
            'company_id' => 'required|numeric',

        ],
        [
            'category_id.between' => 'In valid category id'
        ]);
        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function getSrListOfTse($tse_id){
        $inputs = Input::json()->all();
        /*if(!$this->validatelistOfSRRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = $this->error_code;
            $this->response['message']  = $this->invalid_msg;
            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
            return response($this->response, $this->error_code);
        }*/

        $is_results = $this->employee->getSrListOfTse($inputs, $tse_id);
        if($is_results){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);
    }

    protected function validatelistOfSRRequest($inputs)
    {
        $validator = Validator::make($inputs, [
            'tse_id' => 'required|numeric',
            'company_id' => 'required|numeric'
        ],
        [
            'category_id.between' => 'In valid category id'
        ]);
        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function getTseChartData(Request $request, $tse_id, $chart_type){
        $is_results = $this->employee->getTseChartData($request, $tse_id, $chart_type);
        if($is_results){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['data']     = [];
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);
    }
}
