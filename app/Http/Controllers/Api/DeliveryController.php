<?php

namespace App\Http\Controllers\Api;

use App\DB\Admin\Delivery;
use App\DB\Admin\TrackingDetails;
use App\DB\Admin\TrackingDetailsSummary;
use Dropbox\Exception;
use Illuminate\Http\Request;

//Added
use App\Http\Controllers\Controller;
use App\Repositories\Api\Delivery\DeliveryRepository;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class DeliveryController extends Controller
{
    protected $_errors;
    protected $_error_single_arr;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;
    protected $delivery;

    function __construct(DeliveryRepository $delivery) {
        $this->delivery = $delivery;
        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
        date_default_timezone_set('Asia/Dhaka');
    }

    public function consignmentTrack($consignment_id = null){
        try {
            $response = [];
            $msg = 'Missing consignment id !';
            if ($consignment_id) {
                $delivery = Delivery::select('deliveries.*','mem.mobile_no',
                    DB::raw('CONCAT(m.first_name, " ", m.last_name) AS merchant_name'))
                    ->join('merchants as m', 'm.id','=','deliveries.merchant_id')
                    ->join('members as mem', 'm.member_id','=','mem.id')
                    ->where('consignment_id',$consignment_id)
                    ->first();
                if (!empty($delivery)) {
                    $msg = '';
                    $trackingDetails = TrackingDetails::select('tracking_details.notes','tracking_details.description','tracking_details.created_at','fs.flag_text AS title')
                        ->join("flag_status as fs", "fs.id","=","tracking_details.flag_status_id")
                        ->where('deliveries_id', $delivery->id)
                        ->get();
                    $response['merchant_info'] = array(
                        'merchant_name' => $delivery->merchant_name,
                        'merchant_contact' => $delivery->mobile_no,
                    );
                    $response['shipping_info'] = array(
                      'recipient_name' => $delivery->recipient_name,
                      'recipient_number' => $delivery->recipient_number,
                      'recipient_email' => $delivery->recipient_email,
                      'recipient_address' => $delivery->recipient_address,
                    );
                    $response['order_information'] = array(
                      'consignment_id' => $delivery->consignment_id,
                      'merchant_order_id' => $delivery->merchant_order_id,
                      'ordered_date' => $delivery->created_at,
                      'delivery_date' => $delivery->delivery_date,
                      'receivable_amount' => $delivery->amount_to_be_collected,
                      'special_instructions' => $delivery->special_instruction,
                    );
                    $response['tracking_details'] = $trackingDetails;

                } else {
                    $msg = 'Did not found data by your providing consignment id !';
                }
            }
            if (empty($trackingDetails)) {
                $this->response['success']  = false;
                $this->response['code']     = 404;
                $this->response['message']  = $msg;
                $this->response['data']     = [];
                $this->response['error']    = get_error_response(404, "Didn't found any tracking details!");
                return response($this->response, 404);
            }

            $this->response['success'] = true;
            $this->response['code'] = $this->success_code;
            $this->response['data'] = $response;
            return response($this->response, 200);
        }catch (Exception $exception)
        {
            $this->response['success']  = false;
            $this->response['code']     = 404;
            $this->response['message']  = $exception->getMessage();
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, $exception->getMessage());
            return response($this->response, 404);
        }
    }

    public function getDeliveryList(Request $request)
    {
        $deliveries = $this->delivery->getDeliveryList($request, $request->get('per_page') == '' ? 50 : $request->get('per_page'));
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

    public function getDeliveryById(Request $request, $id)
    {
        $delivery = $this->delivery->findDelivery($id);

        if (empty($delivery)) {
            $this->response['success']  = false;
            $this->response['code']     = '200';
            $this->response['message']  = "Didn't found any delivery !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't find delivery by the id {$id}");
            return response($this->response, 200);
        }

        $delivery->tracking_details = $this->delivery->getDeliveryTrackingLogs($id);
        $this->response['success'] = true;
        $this->response['message']  = "Found deliver by the id {$id}";
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $delivery;
        $this->response['error'] = null;
        return response($this->response, 200);
    }

    public function postStoreDelivery($id = null)
    {
        $msg = "Delivery successfully added !";
        $inputs = Input::json()->all();
        if(!$this->validateDeliveryAddingRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = '422';
            $this->response['message']  = "The given data failed to pass validation !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(422, "The given data failed to pass validation !", $this->_errors, $this->getErrorAsString());
            return response($this->response, 200);
        }

        api_logs("delivery/add", "Merchant end", "M_007" , json_encode($inputs), "During trying to add/edit delivery", 'Delivery add ', 007);
        $delivery_id = $this->delivery->postStoreDelivery($inputs, $id);
        if($delivery_id > 0 || $delivery_id == 'i' || $delivery_id == 'f'){
            if ($delivery_id == 'i')
            {
                $msg = "Couldn't update the consignment data because it has been already accepted by admin. Please contact with system admin.";
            }elseif ($delivery_id == 'f') {
                $msg = "Data saved but notification couldn't send to the rider because device token not found.";
            }elseif ($id > 0){
                $msg = 'Delivery successfully updated';
            }else{
                $msg = $msg;
            }
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $delivery_id;
            $this->response['message']  = $msg;
            $this->response['error']    = null;
            return response($this->response, $this->success_code);
        }


        $msgs = "Couldn't add delivery !";

        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = $msgs;
        $this->response['data']     = 0;
        $this->response['error']    = get_error_response(422, $msgs, []);
        return response($this->response, $this->error_code);
    }

    protected function validateDeliveryAddingRequest($inputs){
        $validator = Validator::make($inputs, [
            'recipient_name'        => 'required|min:2|max:100',
            'recipient_number'      => 'required|min:2|max:20',
            'recipient_zone_id'     => 'required|numeric',
//            'recipient_email'       => 'email',
            'recipient_address'     => 'required|min:2|max:500',
           // 'store_id'              => 'required|numeric',
            'plan_id'               => 'required|numeric'
        ],
        [
            'recipient_zone_id.required' => 'Please select zone !',
            'recipient_zone_id.numeric' => 'Invalid zone selection !',
           // 'store_id.required' => 'Please select store !',
          //  'store_id.numeric' => 'Invalid store selection !',
            'plan_id.required' => 'Please select plan !',
            'plan_id.numeric' => 'Invalid plan selection !',
        ]);

        if ($validator->fails()) {
            $this->_error_single_arr = $validator->errors()->all();
            $this->_errors = $validator->errors()->getMessages();
            return false;
        }
        return true;
    }

    private function getErrorAsString() {
        $errorString ="";
        foreach ($this->_error_single_arr as $error) {
            $errorString .= $error.",";
        }
        return rtrim($errorString,',');
    }
}
