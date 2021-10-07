<?php

namespace App\Http\Controllers\Api;

use App\DB\Admin\CourierZones;
use App\DB\Admin\Hub;
use App\DB\Api\Agent;
use App\Repositories\Api\Delivery\DeliveryRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Api\Rider\RiderRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\RiderRequest;
use App\Http\Requests\Admin\RiderEditRequest;
use Excel;
use Validator;
use DB;
use Illuminate\Support\Facades\Input;

/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class RiderController extends Controller
{
    /**
     * @var
     */
    protected $_errors;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;
    protected $delivery;

    /**
     * @var DriverRepository
     */
    protected $rider;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * RiderController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        RiderRepository $rider
        , RoleRepository $roles
        , MemberRepository $member
        ,DeliveryRepository $delivery
        , AddressRepository $address)
    {
        $this->rider = $rider;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
        $this->delivery = $delivery;
        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getPickupList(Request $request)
    {
        $inputs = Input::json()->all();
        $deliveries = $this->rider->getPickupList($inputs,  !empty($inputs->per_page) ? $inputs->per_page : 20);
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any delivery !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any delivery!");
            return response($this->response, 200);
        }
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $deliveries;
        return response($this->response, 200);
    }

    public function confirmPickup(Request $request)
    {
        $inputs = Input::json()->all();
        $inputs['flag_status_id'] = 11;
        $inputs['is_hub'] = 0;
        $inputs['notes'] = '';
        $inputs['receive_amount'] = '';
        $inputs['assign_to'] = $inputs['user_id'];
        $deliveries = $this->rider->storeTrackingDetails($inputs);
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any delivery !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any delivery!");
            return response($this->response, 200);
        }
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        //$this->response['data'] = $deliveries;
        $this->response['message']  = "Package successfully collected.";
        return response($this->response, 200);
    }

    public function getDeliveryLists(Request $request)
    {
        $inputs = Input::json()->all();
        $inputs['flag_status_id'] = !empty($inputs['flag_status']) ? $inputs['flag_status'] : 10;
        $inputs['is_hub'] = 0;
        $deliveries = $this->rider->getDeliveryLists($inputs,  !empty($inputs->per_page) ? $inputs->per_page : 20);
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any delivery !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any delivery!");
            return response($this->response, 200);
        }

        $deliveries = $deliveries->toArray();
        $deliveries['items'] = $deliveries['data'];
        unset($deliveries['data']);
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $deliveries;
        return response($this->response, 200);
    }

    public function confirmDelivery(Request $request)
    {
        $flagStatus = '';
        $inputs = Input::json()->all();
        if(!$this->validateDeliveryConfirmRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = '422';
            $this->response['message']  = "The given data failed to pass validation !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(422, "The given data failed to pass validation !", $this->_errors, $this->getErrorAsString());
            return response($this->response, 200);
        }
        switch ($inputs['flag_status_id'])
        {
            case 6:
                $flagStatus = 'successfully DELIVERED.';
            break;

            case 7:
                $flagStatus = 'RETURNED FROM HUB.';
            break;

            case 8:
                $flagStatus = 'RETURNED.';
            break;

            case 9:
                $flagStatus = 'ON HOLD.';
            break;

            case 20:
                $flagStatus = 'RESCHEDULED';
            break;
        }
        $inputs['is_hub'] = 0;
        $deliveries = $this->rider->storeTrackingDetails($inputs);
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any delivery !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any delivery!");
            return response($this->response, 200);
        }
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
//        $this->response['data'] = $deliveries;
        $this->response['message']  = "Package  ".$flagStatus;
        return response($this->response, 200);
    }

    protected function validateDeliveryConfirmRequest($inputs){
        $validator = Validator::make($inputs, [
            'user_id'        => 'required|numeric',
            'deliveried_id'      => 'required|numeric',
            'flag_status_id'     => 'required|numeric',
            'receive_amount'     => 'required',
        ],
        [

        ]);

        if ($validator->fails()) {
            $this->_error_single_arr = $validator->errors()->all();
            $this->_errors = $validator->errors()->getMessages();
            return false;
        }
        return true;
    }

    public function sendSmsRecipient(Request $request)
    {
        $inputs = Input::json()->all();
        if(!$this->validateSendSmsRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = '422';
            $this->response['message']  = "The given data failed to pass validation !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(422, "The given data failed to pass validation !", $this->_errors, $this->getErrorAsString());
            return response($this->response, 200);
        }
        $mobile_no = $inputs['mobile_no'];
        $text = $inputs['message'];
        $checkMobile = $this->checkMobileExists($inputs);
        if (empty($checkMobile))
        {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Recipient mobile number is not correct.";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any delivery!");
            return response($this->response, 200);
        }
        if (send_sms($mobile_no, $text) == false) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Something went wrong! Message couldn't send";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any delivery!");
            return response($this->response, 200);
        }
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
//        $this->response['data'] = $deliveries;
        $this->response['message']  = "Message Send successfully.";
        return response($this->response, 200);
    }

    protected function validateSendSmsRequest($inputs){
        $validator = Validator::make($inputs, [
            'mobile_no'        => 'required',
            'message'      => 'required',
            'delivery_id'      => 'required|numeric',
        ],
        [

        ]);

        if ($validator->fails()) {
            $this->_error_single_arr = $validator->errors()->all();
            $this->_errors = $validator->errors()->getMessages();
            return false;
        }
        return true;
    }

    private function checkMobileExists($inputs)
    {
        return DB::table("deliveries")
            ->where([
                "id" => $inputs['delivery_id'],
                "recipient_number" => $inputs['mobile_no'],
                ])
            ->get();
    }

    private function getErrorAsString() {
        $errorString ="";
        foreach ($this->_error_single_arr as $error) {
            $errorString .= $error.",";
        }
        return rtrim($errorString,',');
    }

    public function getProductListsByConsignmentId(Request $request,$delivery_id)
    {
        $inputs = Input::json()->all();
        $deliveries = $this->rider->getProductListsByConsignmentId($delivery_id,  !empty($inputs->per_page) ? $inputs->per_page : 20);
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any product of given delivery id!";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any product of given delivery id!");
            return response($this->response, 200);
        }

        $deliveries = $deliveries->toArray();
        $deliveries['items'] = $deliveries['data'];
        unset($deliveries['data']);
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $deliveries;
        return response($this->response, 200);
    }

    public function getFlagStatus(Request $request)
    {
        $deliveries = $this->rider->getFlagStatus($request);
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any product of given delivery id!";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any product of given delivery id!");
            return response($this->response, 200);
        }

        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $deliveries;
        return response($this->response, 200);
    }

    public function getPickUpDashboard(Request $request)
    {
        $inputs = Input::json()->all();
        $deliveries = $this->rider->getPickUpDashboard($inputs);
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any data!";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any data!");
            return response($this->response, 200);
        }

        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $deliveries;
        return response($this->response, 200);
    }

    public function getDeliveryDashboard(Request $request)
    {
        $inputs = Input::json()->all();
        $inputs['flag_status_id'] = 10;
        $inputs['is_hub'] = 0;
        $deliveries = $this->rider->getDeliveryDashboard($inputs);
        if (empty($deliveries)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any data!";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any data!");
            return response($this->response, 200);
        }

        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $deliveries;
        return response($this->response, 200);
    }
    
}
