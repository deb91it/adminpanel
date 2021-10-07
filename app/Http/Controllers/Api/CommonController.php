<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Repositories\Api\CommonTask\CommonTaskRepository;
use Validator;
use DB;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    protected $common_task;
    protected $success_code;
    protected $error_code;
    protected $merchant_id;

    function __construct(CommonTaskRepository $common_task, Request $request)
    {
        $this->common_task = $common_task;
        $this->success_code = 200;
        $this->error_code = 200;
        date_default_timezone_set('Asia/Dhaka');
        $this->merchant_id = getMerchantId($request->header('Authorization'));
    }

    public function getCategoryList()
    {
        $stores = DB::table('categories')
            ->select('id', 'name')
            ->where('status', 1)
            ->get();

        if (empty($stores)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any Category !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any store!");
            return response($this->response, 200);
        }
        $this->response['success']  = true;
        $this->response['code']     = $this->success_code;
        $this->response['message']  = "Category available !";
        $this->response['data']     = $stores;
        return response($this->response, 200);
    }

    public function getZoneList()
    {
        $zones = DB::table('courier_zones')
            ->select('id as zone_id', 'zone_name')
            ->where('status', 1)
            ->get();

        if (empty($zones)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any Zone !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any zone!");
            return response($this->response, 200);
        }
        $this->response['success']  = true;
        $this->response['code']     = $this->success_code;
        $this->response['message']  = "Zone available !";
        $this->response['data']     = $zones;
        return response($this->response, 200);
    }

    public function getPlanList(Request $request)
    {
        $zones = DB::table('plans as p')
            ->select('p.id as plan_id',
                DB::raw('CONCAT(p.plan_name, " - code: ", p.plan_code) AS plan_name')
            )
            ->join('plan_assign_to_merchant as pam', "p.id", "=", "pam.plan_id");
        if ($request->has('plan_type') && $request->get('plan_type'))
        {
            $zones = $zones->where("p.plan_type", $request->get('plan_type'));
        }
        $zones = $zones
            ->where(['p.status' => 1, 'pam.merchant_id' => $this->merchant_id])
            ->groupBy('p.id')
            ->get();

        if (empty($zones)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any Plan !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any plan !");
            return response($this->response, 200);
        }
        $this->response['success']  = true;
        $this->response['code']     = $this->success_code;
        $this->response['message']  = "Plan available !";
        $this->response['data']     = $zones;
        return response($this->response, 200);
    }

}
