<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

//Added
use App\Http\Controllers\Controller;
use App\Repositories\Api\Dashboard\DashboardRepository;
use Validator;
use DB;

class DashboardController extends Controller
{
    protected $_errors;
    protected $_error_single_arr;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;
    protected $dashboard;

    function __construct(DashboardRepository $dashboard) {
        $this->dashboard = $dashboard;
        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getCountedData(Request $request)
    {
        $deliveries = $this->dashboard->getCountedData($request);
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any dashboard data !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any dashboard data !");
            return response($this->response, 200);
        }

//        $deliveries->total_uninvoiced = $deliveries->todays_sale - $deliveries->total_shipping;
//
//        if ($deliveries->total_uninvoiced > 0) {
//            $deliveries->total_sale = $deliveries->todays_sale;
//        }

        $deliveries = array_map('intval', (array) $deliveries);

        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $deliveries;
        return response($this->response, 200);
    }

    public function getGraphData(Request $request)
    {
        $graph = $this->dashboard->getGraphData($request);
        if (empty($graph)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any dashboard graph data !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any dashboard graph data !");
            return response($this->response, 200);
        }

        $this->response['success'] = true;
        $this->response['message']  = "Found dashboard graph data";
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $graph;
        $this->response['error'] = null;
        return response($this->response, 200);
    }

    private function getErrorAsString() {
        $errorString ="";
        foreach ($this->_error_single_arr as $error) {
            $errorString .= $error.",";
        }
        return $errorString;
    }

    public function getSendTestSms()
    {
        $mobile = '01712348349';
        $text= 'Sms integration is going on !';

        $sts = send_sms($mobile, $text);

        echo "====<br/>";
        print_r($sts);
        echo "<br/>====";
        exit();


        return true;
    }
}
