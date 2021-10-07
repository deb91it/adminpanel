<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
//Added
use App\Http\Controllers\Controller;
use App\Repositories\Api\Store\StoreRepository;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class StoreController extends Controller
{
    protected $_errors;
    protected $_error_single_arr;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;
    protected $store;

    function __construct(StoreRepository $store) {
        $this->store = $store;
        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getStoreList(Request $request)
    {

        $stores = $this->store->getStoreList($request->get('per_page') == '' ? 50 : $request->get('per_page'));
        if (empty($stores)) {
            $this->response['success']  = false;
            $this->response['code']     = '404';
            $this->response['message']  = "Didn't found any store !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any store!");
            return response($this->response, 200);
        }

        $stores = $stores->toArray();
        $stores['items'] = $stores['data'];
        unset($stores['data']);
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $stores;
        return response($this->response, 200);
    }

    public function getStoreById(Request $request, $id)
    {
        $store = $this->store->findStore($id);
        if (empty($store)) {
            $this->response['success']  = false;
            $this->response['code']     = '404';
            $this->response['message']  = "Didn't find any store by the id {$id} !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "");
            return response($this->response, 200);
        }
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['message']  = "Found store by the id {$id} !";
        $this->response['data'] = $store;
        $this->response['error'] = null;
        return response($this->response, 200);
    }

    public function postAddStore($id = null)
    {
        $msg = "Store successfully added !";
        $inputs = Input::json()->all();
        if(!$this->validateStoreAddingRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = '422';
            $this->response['message']  = "The given data failed to pass validation !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(422, "The given data failed to pass validation !", $this->_errors, $this->getErrorAsString());
            return response($this->response, 200);
        }

        api_logs("store/add", "Merchant end", "M_007" , json_encode($inputs), "During trying to add/edit store", 'Store add ', 007);
        $store_id = $this->store->postAddStore($inputs, $id);
        if($store_id > 0){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $store_id;
            $this->response['message']  = $id > 0 ? 'Store successfully updated !' : $msg;
            $this->response['error']    = null;
            return response($this->response, $this->success_code);
        }

        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't add store";
        $this->response['data']     = 0;
        $this->response['error']    = get_error_response(422, "Couldn't add store !", []);
        return response($this->response, $this->error_code);
    }

    protected function validateStoreAddingRequest($inputs){
        $validator = Validator::make($inputs, [
            'name'              => 'required|min:2|max:40',
            'zone_id'           => 'required|numeric',
            'contact_name'      => 'required|min:2|max:40',
            'contact_phone'     => 'required|min:2|max:20',
            'address'           => 'required|min:2|max:500',
            'categories'        => 'required',
            'is_mart_ready'     => 'required'
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
