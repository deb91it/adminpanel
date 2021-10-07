<?php

namespace App\Http\Controllers\Api;

use App\DB\Api\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Api\Member\MemberRepository;
use App\Repositories\Api\Merchant\MerchantRepository;
use App\Repositories\Api\Agent\AgentRepository;
use App\Repositories\Api\CommonTask\CommonTaskRepository;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use Auth;


class AuthController extends Controller
{
    protected $_errors;
    protected $member;
    protected $agent;
    protected $common_task;
    protected $merchant;
    protected $success_code;
    protected $error_code;

    function __construct(
        MemberRepository $member
        , MerchantRepository $merchant
        , AgentRepository $agent
        , CommonTaskRepository $common_task
    )
    {
        $this->member = $member;
        $this->merchant = $merchant;
        $this->agent = $agent;
        $this->common_task = $common_task;
        $this->success_code = 200;
        $this->error_code = 200;
        date_default_timezone_set('Asia/Dhaka');
    }

    public function postLogin(Request $request)
    {
        $client = $request->header('user-agent');
        $inputs = Input::json()->all();
        api_logs("post-login", "Controller Action", 0, json_encode($inputs), json_encode($inputs), "success", 0);
        if (!$this->validationLoginRequest($inputs)) {
            return response($this->_errors, 200);
        }
        $user_name = $inputs['user_name'];
        $password = $inputs['password'];
        if (isset($inputs['user_type']) &&  $inputs['user_type'] == 1)
        {
            $auth_info = $this->getRiderAuthInfo($user_name);
        } else {
            $auth_info = $this->getAuthInfo($user_name);
        }

        if (!empty($auth_info)) {
            if (Hash::check($password, $auth_info->password)) {
                $device_id = '';
                if (isset($request->device_id) && !empty($request->device_id)) {
                    $device_id = $request->device_id;
                }
                return response($this->prepareUserLoginResponse($auth_info, $client, $device_id), 200);
            } else {
                return $this->notMatchLogin('Doesn\'t match password !');
            }
        }

        return $this->notMatchLogin('Doesn\'t match email or mobile number !');
    }

    private function notMatchLogin($msg = '')
    {
        $this->response['status'] = 'Not logged in !';
        $this->response['success'] = false;
        $this->response['msg'] = $msg != '' ? $msg : 'Does not match your user_name or password !';
        return response($this->response, 200);
    }

    private function storeDeviceID($member_id, $device_id) {
        $device_id = $this->member->storeDeviceID($member_id, $device_id);
    }

    protected function prepareUserLoginResponse($auth_obj, $client, $device_id)
    {

        $login_response = [];
        //Make and save token to DB
        $token = $this->makeToken($auth_obj->id, $client, $auth_obj->user_type);
        if (!empty($device_id)) {
            $device_id = $this->storeDeviceID($auth_obj->id, $device_id);
        }

        $member_type =$auth_obj->user_type;
        $login_response['success']          = true;
        $login_response['status']           = 'OK';
        $login_response['token']            = $token;
        $login_response['member_type']      = $member_type;
        $login_response['user_name']        = $auth_obj->username;
        $login_response['email']            = $auth_obj->email;
        $login_response['mobile_no']        = $auth_obj->mobile_no;
        $login_response['member_id']        = $auth_obj->id;

        if (($auth_obj->user_type) == 1)
        {
            $login_response['zone_id'] = $auth_obj->zone_id;
        }
        else{
            $login_response['business_name'] = $auth_obj->business_name;
            $login_response['merchant_code']    = $auth_obj->merchant_code;
        }



        $user_info = $this->{$auth_obj->model_key}->getUserDetails($auth_obj->id);

        if (!empty($user_info)) {
            $image_url = "";
            if ($user_info->profile_pic != '') {
                $image = public_path('resources/profile_pic/') . $user_info->profile_pic;
                if (file_exists($image)) {
                    $image_url = url('/resources/profile_pic') . "/" . $user_info->profile_pic;
                }
            }
            $login_response['first_name'] = $user_info->first_name;
            $login_response['last_name'] = $user_info->last_name;
            $login_response['profile_picture_url'] = $image_url;
            $login_response['user_id'] = $user_info->id;
        }

        $login_response['msg'] = 'Successfully logged in !';
        return $login_response;
    }

    protected function getAuthInfo($user_name)
    {
        $info = DB::table('members as a')
            ->select(
                'a.id',
                'a.username',
                'a.email',
                'a.mobile_no',
                'a.password',
                'a.user_type',
                'b.model_key',
                'm.first_name',
                'm.last_name',
                'm.business_name',
                'm.merchant_code'
            )
            ->join('merchants as m', 'm.member_id', '=', 'a.id')
            ->join('models as b', 'b.id', '=', 'a.model_id')
            ->where( [ 'a.is_active' => 1, 'a.can_login' => 1, 'a.user_type' => 2 ])
            ->where(function ($query) use ($user_name) {
                $query->where('a.email', $user_name);
                $query->orWhere('a.mobile_no', $user_name);
            })
            ->first();
        return $info;
    }

    protected function getRiderAuthInfo($user_name)
    {
        $info = DB::table('members as a')
            ->select(
                'a.id',
                'a.username',
                'a.email',
                'a.mobile_no',
                'a.password',
                'a.user_type',
                'b.model_key',
                'r.first_name',
                'r.last_name',
                'r.zone_id'
            )
            ->join('riders as r', 'r.member_id', '=', 'a.id')
            ->join('models as b', 'b.id', '=', 'a.model_id')
            ->where( [ 'a.is_active' => 1, 'a.can_login' => 1, 'a.user_type' => 1 ])
            ->where(function ($query) use ($user_name) {
                $query->where('a.email', $user_name);
                $query->orWhere('a.mobile_no', $user_name);
            })
            ->first();
        return $info;
    }

    public function postCheckMobile(Request $request)
    {
        $inputs = Input::json()->all();

        if(!$this->validationCheckMobileRequest($inputs)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = 'Invalid request';
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }

        $authentication_code = '';
        $signin = true;
        $signup = false;
        $signup_data = false;
        $isFirstLogin = false;

        $info = DB::table('members')
            ->select('id', 'mobile_no', 'password', 'activation_code','user_type')
            ->where('mobile_no', $inputs['mobile_no'])
            ->first();

        //If data is found by this mobile number and user type
        if (! empty($info)) {

            $user_data = DB::table('members as m')
                ->select('m.id', 'm.password','m.isfirstlogin', 'u.first_name', 'u.last_name')
                ->leftJoin(get_user_table_by_user_type($info->user_type). ' as u','u.member_id', '=', 'm.id')
                ->where('m.id', $info->id)
//                ->where('m.password', ' ')
//                ->where('m.isfirstlogin', 0)
                ->first();
            //print_r($info); exit();

            //If some field is missing in users table(passenger, driver, merchant)

            if ('' == $user_data->password && 0 == $user_data->isfirstlogin) {
                //echo 'ss';exit();
                $signin = false;
                $signup = false;
                $signup_data = true;
                $isFirstLogin = true;

                $this->makeVerifyMobileNumberCode($info->mobile_no,$info->user_type);


                //Check if already exist authentication code
                if ($info->activation_code == '') {
                    //Make new code
                    $auth_code = get_generated_code(20);
                    $member = Member::find($info->id);
                    $member->activation_code  = $auth_code;
                    if ($member->save()) {
                        $authentication_code = $auth_code;
                    }
                } else {
                    $authentication_code = $info->activation_code;
                }
                //return $authentication_code;

                //Send sms


            }
            //If any field is equal to empty then sign in will be true , that is already assign

            // If data is not found by this mobile number and user type, then go to the signup
        } else {
            $signin = false;
            $signup = true;

        }

        $this->response['signin'] = $signin;
        $this->response['signup'] = $signup;
        $this->response['signup_data'] = $signup_data;
        $this->response['isFirstLogin'] = $isFirstLogin;
        $this->response['authentication_code'] = $authentication_code;
        $this->response['code'] = '200';
        $this->response['message'] = "";
        return response($this->response, 200);
    }

    protected function validationCheckMobileRequest($inputs){

        $validator = Validator::make($inputs, [
            'mobile_no' => 'required|min:11|max:11',
//            'user_type' => 'required|numeric|between:1,3'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    protected function makeVerifyMobileNumberCode($mobile_no, $user_type)
    {
        DB::table('verify_mobile_no')
            ->where(['status'=> 1, 'mobile_no'=> $mobile_no, 'user_type'=>  $user_type, 'purpose'=>  0])
            ->update(['status' => 2]);

        $code = makeUniqueNumericCode(5);

        DB::table('verify_mobile_no')->insert(
            [
                'mobile_no'         => $mobile_no,
                'code'              => $code,
                'user_type'         => $user_type,
                'purpose'           => 0,
                'created_at'        => date('Y-m-d H:i:s'),
                'expire_at'         => "2025-01-06 10:12:03"
            ]
        );

        //Send sms
        if ($code != '') {
            send_sms($mobile_no, "Your verification code is ".$code);
            return true;
        }

        return $code;
    }

    // Verify mobile number during registration
    public function postVerifyCode(Request $request)
    {
        $inputs = Input::json()->all();

        if(!$this->validationVerifyCodeRequest($inputs)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = 'Invalid request';
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }


        if ($this->verifyActivationCode($inputs['mobile_no'], $inputs['user_type'], $inputs['code'])) {

            if (array_key_exists("member_id",$inputs) && $inputs['member_id'] != "")
            {
                $info = DB::table('members')
                    ->select('activation_code')
                    ->where('id', $inputs['member_id'])
                    ->where('user_type', $inputs['user_type'])
                    ->first();
                if (! empty($info)) {

                    $auth_code = $info->activation_code!="" ? $info->activation_code : get_generated_code(20);

                    DB::table('members')
                        ->where(['id' => $inputs['member_id']])
                        ->where('user_type', $inputs['user_type'])
                        ->update(['mobile_no' => $inputs['mobile_no'], 'activation_code' => $auth_code]);

                }
                else {
                    $this->response['success'] = false;
                    $this->response['code'] = '200';
                    $this->response['message'] = "Invalid member_id";
                    $this->response['error'] = "Invalid member_id";
                }
            }
            else {
                // Insert to member table
                if ($inputs['user_type'] == 1) {
                    $model_id = 2;
                } else if ($inputs['user_type'] == 2) {
                    $model_id = 3;
                } else {
                    $model_id = 4;
                }
                $role_id = $model_id;
                $auth_code = get_generated_code(20);
                $this->insertToMemberTable(['mobile_no' => $inputs['mobile_no'], 'user_type' => $inputs['user_type'], 'model_id' => $model_id, 'activation_code' => $auth_code, 'created_at' => date('Y-m-d H:i:s')], $role_id);

            }
            $this->response['success'] = true;
            $this->response['authentication_code'] = $auth_code;
            $this->response['code'] = '200';
            $this->response['message'] = "Successfully verify the code";
        } else {
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = "Invalid code";
            $this->response['error'] = "Invalid code";
        }
        return response($this->response, 200);
    }

    protected function validationVerifyCodeRequest($inputs){

        $validator = Validator::make($inputs, [
            'mobile_no' => 'required|min:11|max:11',
            'code' => 'required|max:5',
            'user_type' => 'required|numeric|between:1,3'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    protected function verifyActivationCode($mobile_no, $user_type, $code, $purpose = 0)
    {
        $info = DB::table('verify_mobile_no')
            ->select('id', 'mobile_no')
            ->where(['status'=> 1, 'mobile_no'=> $mobile_no, 'user_type'=>  $user_type, 'code'=>  $code, 'purpose'=>  $purpose])
            ->first();

        if (empty($info)) {
            return false;
        }

        DB::table('verify_mobile_no')
            ->select('id', 'mobile_no')
            ->where(['status'=> 1, 'mobile_no'=> $mobile_no, 'user_type'=>  $user_type, 'code'=>  $code, 'purpose'=>  $purpose])
            ->update(['status' => 0]);

        return true;
    }

    private function getErrorAsString(){
        $errorString ="";
        foreach ($this->_errors as $error) {
            $errorString .= $error.",";
        }
        return $errorString;
    }

    public function postForgotPasswordRequest(Request $request)
    {
        $inputs = Input::json()->all();

        if(!$this->validationRequestForgotPasswordRequest($inputs)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = $this->getErrorAsString();
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }

        $info = DB::table('members')
            ->select('id', 'mobile_no')
            ->where([
                'mobile_no' => $inputs['mobile_no'],
    //            'user_type' => $inputs['user_type']
            ])
            ->first();

        if (!empty($info)) {
            // Unset all previous token of this user if exist

            DB::table('password_resets')
                ->where(['member_id'=> $info->id])
                ->update([
                    'status' => 1
                ]);

            //Make new token for this request
            while(true) {
                $token = makeUniqueNumericCode(5);
                if ($this->isExistTokenInDb($token)) {
                    break;
                }
            }

            DB::table('password_resets')
                ->insert([
                    [
                        'member_id'     => $info->id,
                        'mobile_no'     => $inputs['mobile_no'],
                        'email'     => 'demo@example.com',
                        'old_password'     => 'demo@example.com',
                        'token'         => $token,
                        'token_expire'  => date("Y-m-d H:i:s", strtotime("+12 hours"))
                    ]
                ]);
            //Send sms
            $mobile = substr($info->mobile_no,0, 3).'*****'.substr($info->mobile_no, -3);
            send_sms($info->mobile_no, "Your password reset verification token is ".$token);

            $this->response['success'] = true;
            $this->response['code'] = '200';
            $this->response['tokencode'] = $token;
            $this->response['message'] = "Verification token has send to this number ".$mobile;
            $this->response['error'] = get_error_response(200,  "Verification token has send to this number ".$mobile);
            return response($this->response, 200);

        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = "Your provided mobile number is not valid";
        $this->response['error'] = get_error_response(200,  "Your provided mobile number is not valid");
        return response($this->response, 200);
    }

    protected function validationRequestForgotPasswordRequest($inputs){

        $validator = Validator::make($inputs, [
            'mobile_no'  => 'required|min:11|max:11|exists:members',
            //'user_type'  => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function postForgotPassword(Request $request)
    {
        $client =  $request->header('user-agent');
        $inputs = Input::json()->all();

        if(!$this->validationForgotPasswordRequest($inputs)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = $this->getErrorAsString();
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }

        $info = DB::table('password_resets')
            ->select('id', 'member_id', 'mobile_no')
            ->where('token', $inputs['token'])
            ->where('token_expire', '>', date("Y-m-d H:i:s"))
            ->where('status', 0)
            ->first();


        if (!empty($info)) {
            $old_pass = '';
            $user = DB::table('members')
                ->select('password')
                ->where('id', $info->member_id)
                ->first();
            if (!empty($user)) {
                $old_pass = $user->password;
            }

            DB::table('password_resets')
                ->where(['id'=> $info->id])
                ->update([
                    'token'  => '',
                    'old_password' => $old_pass,
                    'status' => 1
                ]);

            //$new_password = makeUniqueNumericCode(6);
            DB::table('members')
                ->where(['id'=> $info->member_id])
                ->update(['password' => bcrypt($inputs['new_password']), 'is_active' => 1, 'can_login' => 1,'isfirstlogin'=>1]);

            //Send sms
            //send_sms($info->mobile_no, "Your password has been changed, your new password is ".$new_password);

            //$mobile = substr($info->mobile_no,0, 3).'*****'.substr($info->mobile_no, -3);

            $checkUser = Member::find($info->member_id);
            if (!empty($checkUser) && $checkUser->user_type == 1) {
                $auth_info = DB::table('members as a')
                    ->select('a.id', 'a.username','a.email', 'a.mobile_no', 'a.user_type', 'b.model_key','r.zone_id')
                    ->join('models as b', 'b.id', '=', 'a.model_id')
                    ->join('riders as r', 'r.member_id', '=', 'a.id')
                    ->where(['a.id' => $info->member_id])
                    ->where('a.user_type', '!=', 0)
                    ->first();
            }else{
                $auth_info = DB::table('members as a')
                    ->select('a.id', 'a.username','a.email', 'a.mobile_no', 'a.user_type', 'b.model_key', 'm.business_name')
                    ->join('models as b', 'b.id', '=', 'a.model_id')
                    ->join('merchants as m', 'm.member_id', '=', 'a.id')
                    ->where(['a.id' => $info->member_id])
                    ->where('a.user_type', '!=', 0)
                    ->first();
            }
            if (!empty($auth_info)) {
                $device_id = '';
                if (isset( $inputs['device_id'])) {
                    $device_id = $inputs['device_id'];
                }
                return response($this->prepareUserLoginResponse($auth_info, $client, $device_id) , 200);
            }
        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = "Token validation time expired or invalid token";
        $this->response['error'] = get_error_response(200,  "Token validation time expired  invalid token");
        return response($this->response, 200);
    }

    protected function validationForgotPasswordRequest($inputs){

        $validator = Validator::make($inputs, [
            'token'  => 'required|exists:password_resets,token,status,0',
            'new_password'      => 'required|min:6|max:60',
        ],
            [
                'token.exists'=>'Provided verification code is not valid'
            ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    protected function isExistTokenInDb($token) {
        $data = DB::table('password_resets')
            ->select('id')
            ->where('token', $token)
            ->get();
        if (empty($data)) {
            return true;
        }
        return false;
    }

    public function postChangePassword(Request $request)
    {
        $inputs = Input::json()->all();

        if(!$this->validationChangePasswordRequest($inputs)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = 'Failed to change password';
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }

        $info = DB::table('members')
            ->select('id', 'mobile_no', 'password')
            ->where('id', $inputs['member_id'])
            ->first();
        if (!empty($info)) {
            if (Hash::check($inputs['current_password'], $info->password)) {
                DB::table('members')
                    ->where(['id'=> $info->id, 'mobile_no'=> $info->mobile_no])
                    ->update(['password' => bcrypt($inputs['new_password'])]);
                //Send sms
                //send_sms($info->mobile_no, "Your password has been changed successfully");

                $this->response['success'] = true;
                $this->response['code'] = '200';
                $this->response['message'] = "Your password has been changed successfully";
                $this->response['error'] = get_error_response(200,  "Your password has been changed successfully");
                return response($this->response, 200);
            }
        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = "Doesn't match your current password";
        $this->response['error'] = get_error_response(200,  "Doesn't match your current password");
        return response($this->response, 200);
    }

    protected function validationChangePasswordRequest($inputs){

        $validator = Validator::make($inputs, [
            'member_id'             => 'required|numeric',
            'current_password'      => 'required|min:6',
            'new_password'          => 'required|min:6|max:60|different:current_password',
            'confirm_new_password'  => 'required|min:6|max:60|same:new_password'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    protected function makeToken($member_id, $client, $member_type)
    {
        $token = hash('sha256', str_random(20), false);
        //Set expire previous token
//        DB::table('member_tokens')
//            ->where(['member_id' => $member_id])
//            ->update(['is_expire' => 1]);

        DB::table('member_tokens')->insert(
            [
                'member_id' => $member_id,
                'member_type' => $member_type,
                'token' => $token,
                'client' => $client,
                'expire_at' => date("Y-m-d H:i:s", strtotime("+365 day"))
            ]
        );
        return $token;
    }

    protected function validationLoginRequest($inputs)
    {
        $validator = Validator::make($inputs, [
            'user_name' => 'required',
            'password'  => 'required'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function getVerifyAuthentication(Request $request)
    {
        $token = $request->header('Authorization');
        $token_data = DB::table('member_tokens as mt')
            ->select('m.id')
            ->join('merchants as m','m.member_id', '=', 'mt.member_id')
            ->where([ 'mt.member_type' => 2, 'mt.token' => $token, 'is_expire' => 0 ])
            ->first();
        if(!empty($token_data)){
            return response(['success' => true], 200);
        }
        return response(['success' => false], 420);
    }

    public function getEmployeeData($user_id, $user_type)
    {

        //api_logs("get_profile_data", "TSE/TSR", "TSE/SR_{$user_id}" , json_encode([$user_id]), "what happen to you", 'storeDriver Sign Updatdat', $user_id);
        $data = $this->common_task->getEmployeeData($user_id,$user_type);
        if(!empty($data)){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['response'] = $data;
            $this->response['message']  = "Result found successfully";
            return response((array) $this->response, $this->success_code);
        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = "Didn't found profile data";
        $this->response['error'] = get_error_response(200, "Didn't found profile data");
        return response($this->response, 200);
    }

    public function profileUpdate(Request $request)
    {
        $inputs = Input::json()->all();
        //api_logs("save_profile_data", "TSE/TSR", "TSE/SR_{$user_id}" , json_encode([$user_id]), "what happen to you", 'storeDriver Sign Updatdat', $user_id);
        if(!$this->validationProfileRequest($inputs)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = 'Invalid request';
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }
        $data = $this->common_task->updateProfile($inputs);
        if($data > 0){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['response'] = $data;
            $this->response['message']  = "Profile updated successfully";
            return response((array) $this->response, $this->success_code);
        }
        if ($data == 'i')
        {
            $message = "Password Incorrect";
        }else{
            $message = "Data Couldn't Updated";
        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = $message;
        $this->response['error'] = get_error_response(200, $message);
        return response($this->response, 200);
    }

    public function validationProfileRequest($request)
    {
        $validator = Validator::make($request, [
            'user_id' => 'required',
            'user_type'  => 'required',
            'business_name' => 'required_if:user_type,==,2',
            'gender' => 'required_if:user_type,==,1',
            'date_of_birth' => 'required_if:user_type,==,1',
            'full_name' => 'required_if:user_type,==,1',
        ],
            [
               "business_name.required_if" => "Business name is required.",
               "date_of_birth.required_if" => "Date of birth is required.",
               "gender.required_if" => "Gender is required.",
               "gender.required_if" => "Gender is required.",
               "full_name.required_if" => "Full name is required.",
            ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function merchantProfileUpdate(Request $request)
    {
        if(!$this->validationMerchantProfileUpdateRequest($request)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = 'Invalid request';
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }
        $data = $this->common_task->updateMerchantProfile($request);
        if($data){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['message']  = "Profile updated successfully";
            $this->response['data']  = [
                'business_name' => $data->business_name,
                'profile_picture_url' => $data->profile_pic_url
            ];
            return response($this->response, $this->success_code);
        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = 'Sorry couldn\'t update profile !';
        $this->response['error'] = get_error_response(200, 'Sorry couldn\'t update profile !');
        return response($this->response, 200);
    }

    public function validationMerchantProfileUpdateRequest($request)
    {
        $validator = Validator::make($request->all(), [
            'businessName' => 'required',
        ],
        [
           "businessName.required" => "Business name is required !"
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function profileImageUpdate(Request $request)
    {
        if(!$this->validationProfileImageUpdateRequest($request)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = 'Invalid request';
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }
        $data = $this->common_task->updateProfileImage($request);
        if($data){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['message']  = "Profile updated successfully";
            $this->response['data']  = [
                'profile_picture_url' => $data->profile_pic_url
            ];
            return response($this->response, $this->success_code);
        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = 'Sorry couldn\'t update profile !';
        $this->response['error'] = get_error_response(200, 'Sorry couldn\'t update profile !');
        return response($this->response, 200);
    }

    public function validationProfileImageUpdateRequest($request)
    {
        $validator = Validator::make($request->all(), [
            'profile_pic' => 'required|mimes:jpeg,jpg,png|max:1000',
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function getLogout(Request $request)
    {
        $header_token = $request->header("Authorization");
        if (empty($header_token)) {
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = "Header token not found";
            $this->response['error'] = get_error_response(200,$this->getErrorAsString());
        }
        $query = DB::table('member_tokens as mt')
            ->select('mt.member_id')
            ->where('token', $header_token)
            ->first();
        if (!empty($query)) {
            DB::table("device_registration_number")->where("member_id", $query->member_id)->delete();
            DB::table('member_tokens')->where('token', $header_token)->delete();
            $this->response['success'] = true;
            $this->response['code'] = '200';
            $this->response['message'] = "User logout successfully.";
            return response($this->response, 200);
        }
        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = "Invalid Token.";
        $this->response['error'] = get_error_response(200,$this->getErrorAsString());

    }
}
