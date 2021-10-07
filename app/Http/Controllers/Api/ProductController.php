<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
//Added
use App\Http\Controllers\Controller;
use App\Repositories\Api\Product\ProductRepository;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    protected $_errors;
    protected $_error_single_arr;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;
    protected $product;

    function __construct(ProductRepository $product) {
        $this->product = $product;
        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getProductList(Request $request)
    {
        $products = $this->product->getProductList($request);
        if (empty($products)) {
            $this->response['success']  = false;
            $this->response['code']     = '404';
            $this->response['message']  = "Didn't found any product !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any product!");
            return response($this->response, 200);
        }
        if($request->get('returnType') == 'list'){
            $products = $products;
        }else {
            $products = $products->toArray();
            $products['items'] = $products['data'];
            unset($products['data']);
        }
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $products;
        return response($this->response, 200);
    }
    public function getProductListByStoreId(Request $request)
    {
        $products = $this->product->getProductListByStoreId($request);
        if (empty($products)) {
            $this->response['success']  = false;
            $this->response['code']     = '404';
            $this->response['message']  = "Didn't found any product !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "Didn't found any product!");
            return response($this->response, 200);
        }

        $products = $products->toArray();
        $products['items'] = $products['data'];
        unset($products['data']);
        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['data'] = $products;
        return response($this->response, 200);
    }

    public function getProductById(Request $request, $id)
    {
        $product = $this->product->findProduct($id);
        if (empty($product)) {
            $this->response['success']  = false;
            $this->response['code']     = '404';
            $this->response['message']  = "Didn't find any product by the id {$id} !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(404, "");
            return response($this->response, 200);
        }

        $this->response['success'] = true;
        $this->response['code'] = $this->success_code;
        $this->response['message']  = "Found product by the id {$id} !";
        $this->response['data'] = $product;
        $this->response['error'] = null;
        return response($this->response, 200);
    }

    public function postStoreProduct($id = null)
    {
        $msg = "Product successfully added !";
        $inputs = Input::json()->all();
        if(!$this->validateProductAddingRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = '422';
            $this->response['message']  = "The given data failed to pass validation !";
            $this->response['data']     = [];
            $this->response['error']    = get_error_response(422, "The given data failed to pass validation !", $this->_errors, $this->getErrorAsString());
            return response($this->response, 200);
        }

        api_logs("product/add", "Merchant end", "M_007" , json_encode($inputs), "During trying to add/edit product", 'Product add ', 007);
        $product_id = $this->product->postStoreProduct($inputs, $id);
        if($product_id > 0){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['data']     = $product_id;
            $this->response['message']  = $id > 0 ? 'Product successfully updated !' : $msg;
            $this->response['error']    = null;
            return response($this->response, $this->success_code);
        }

        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't add product";
        $this->response['data']     = 0;
        $this->response['error']    = get_error_response(422, "Couldn't add product !", []);
        return response($this->response, $this->error_code);
    }

    protected function validateProductAddingRequest($inputs){
        $validator = Validator::make($inputs, [
            'name'              => 'required|min:2|max:40',
            'subtitle'          => 'required|min:2|max:40',
            'store_id'          => 'required|numeric',
            'category_id'       => 'required|numeric',
            'price'             => 'required|numeric',
            'sell_price'        => 'required|numeric'
        ],
        [
            'store_id.required' => 'Please select store !',
            'store_id.numeric' => 'Invalid store selection !',
            'category_id.required' => 'Please select category !',
            'category_id.numeric' => 'Invalid category selection !',
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
