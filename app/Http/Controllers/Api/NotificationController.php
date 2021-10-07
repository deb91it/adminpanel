<?php

namespace App\Http\Controllers\Api;

use App\DB\Notification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Api\Notification\NotificationRepository;
use App\Repositories\Api\Member\MemberRepository;
use Illuminate\Support\Facades\Input;
use Validator;
use Excel;
use DB;
use Response;
use DateTime;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{

    /**
     * @var
     */
    protected $_errors;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;

    /**
     * @var DriverRepository
     */
    protected $notification;
    protected $driver;


//    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $vehicle;



    /**
     * PassengerController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
          MemberRepository $member
        , NotificationRepository $notification
    )

    {
        $this->member = $member;
        $this->notification = $notification;


        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
    }

    private function getErrorAsString(){
        $errorString ="";
        foreach ($this->_errors as $error) {
            $errorString .= $error.",";
        }
        return $errorString;
    }

    /** DashBoard Territory Sales Executive */
    public function merchantNotification(Request $request){
        $this->merchant_id = getMerchantId($request->header('Authorization'));
        $inputs = Input::json()->all();
//        return $inputs;
//        if(!$this->validateDashboardRequest($inputs)){
//            $this->response['success']  = false;
//            $this->response['code']     = $this->error_code;
//            $this->response['message']  = $this->invalid_msg;
//            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
//            return response($this->response, $this->error_code);
//        }

        $is_results = $this->notification->merchantNotification($inputs, $this->merchant_id);
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

    protected function validateDashboardRequest($inputs)
    {
//        return $inputs;
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

}
