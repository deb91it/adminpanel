<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\Passenger\PassengerRepository;
use App\Repositories\Driver\DriverRepository;
use App\Repositories\DriverInvoice\DriverInvoiceRepository;
use App\Repositories\Merchant\MerchantRepository;
use App\Repositories\Member\MemberRepository;
use App\Repositories\TripRequest\TripRequestRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use App\DB\DeviceRegistration;
use App\DB\Member;
use App\DB\Passenger;

class AuthController extends Controller
{
    protected $_errors;

    protected $passenger;
    protected $driver;
    protected $merchant;
    protected $member;

    function __construct(
        PassengerRepository $passenger
        , DriverRepository $driver
        , DriverInvoiceRepository $invoice
        , MerchantRepository $merchant
        , MemberRepository $member
        , TripRequestRepository $trip_request
    )
    {
        $this->passenger = $passenger;
        $this->driver = $driver;        
        $this->invoice = $invoice;        
        $this->merchant = $merchant;
        $this->member = $member;
        $this->trip_request = $trip_request;
        date_default_timezone_set('Asia/Dhaka');
    }

    /*
     *  All services user Login
     */

    public function postLogin(Request $request)
    {
        $client = $request->header('user-agent');
        $inputs = Input::json()->all();
        if(!$this->validationLoginRequest($inputs)){
            return response($this->_errors, 200);
        }
        $mobile_no = $inputs['mobile_no'];
        $password = $inputs['password'];
        $type = $inputs['user_type'];

        $auth_info = $this->getAuthInfo($mobile_no, $type);

        if (!empty($auth_info)) {
            if (Hash::check($password, $auth_info->password)) {
                $device_id = '';
                if (isset( $inputs['device_id'])) {
                    $device_id = $inputs['device_id'];
                }
                return response($this->prepareUserLoginResponse($auth_info, $client, $device_id) , 200);
            }
        }

        $this->response['success'] = false;
        $this->response['msg'] = 'Does not match your mobile or password';
        return response($this->response, 200);
    }

    protected function prepareUserLoginResponse($login_info_obj, $client, $device_id = null){
        $login_response = [];
        //Make and save token to DB
        $token = $this->makeToken($login_info_obj->id, $client, $login_info_obj->user_type);

        $login_response['success']          = true;
        $login_response['token']            = $token;
        $login_response['member_type']      = $login_info_obj->user_type;
        $login_response['user_name']        = $login_info_obj->username;
        $login_response['email']            = $login_info_obj->email;
        $login_response['mobile_no']        = $login_info_obj->mobile_no;
        $login_response['member_id']        = $login_info_obj->id;
        $arStageData = [];
        
        $user_id = 0;
        $user_info = $this->{$login_info_obj->model_key}->getUserDetails($login_info_obj->id);
        
        if ( !empty($user_info)) {
            $image_url = ""; 
            if ($user_info->profile_pic != '') {
                $image = public_path('resources/profile_image/').$user_info->profile_pic;                
                if (file_exists($image)){
                    $image_url = url('/resources/profile_image')."/".$user_info->profile_pic;
                }             
            }
            
            $user_id = $user_info->id;
            $login_response['first_name']           = $user_info->first_name;
            $login_response['last_name']            = $user_info->last_name;
            $login_response['profile_picture_url']  = $image_url;

            if ($login_info_obj->model_key != 'merchant') {
                $login_response['rating_value']         = number_format((double)$this->{$login_info_obj->model_key}->getUserRating($user_info->id), 2);
                $login_response['trip_count']           = $this->{$login_info_obj->model_key}->getUserTripCount($user_info->id);
            }

            $login_response['user_id']              = $user_info->id;
            
            if($login_response['member_type'] == 2)
            {
                $vehicle = DB::table('driver_active_vehicle as dav')
                            ->select('dav.vehicle_id', 'v.category_id')
                            ->join('vehicles as v', 'v.id', '=', 'dav.vehicle_id')
                            ->where(['dav.driver_id' => $user_info->id, 'dav.is_active' => 1])
                            ->first();
                
                if (!empty($vehicle)) {
                    $login_response['vehicle_id'] = $vehicle->vehicle_id;
                    $login_response['vehicle_type'] = $vehicle->category_id;
                }
                
                /*$arStageData = $this->trip_request->driverCurrentStage(array('driver_id' => $user_id, 'request_id' => 798));*/
                $arStageData = $this->trip_request->driverCurrentStage(array('driver_id' => $user_id, 'from' => 'login'));
            }

            //Set is_approve flag for driver
            if ($login_info_obj->user_type == 2) {
                $login_response['is_approve'] = false;
                if ($user_info->status === 1) {
                    $login_response['is_approve'] = true;
                }
            }
            //Save device id
            if (!empty($device_id)) {
                $this->saveDeviceId($device_id, $login_info_obj->id);
            }
            
            if(empty($arStageData))
            {
                $arStageData = $this->trip_request->passengerCurrentStage(array('passenger_id' => $user_id, 'from' => 'login'));
            }
        }

        if ($user_id > 0) {
            $details = $this->{$login_info_obj->model_key}->details($login_info_obj->id, $user_id);
            if ( !empty($user_info)) {
                $login_response['details'] = $details['details'];
                $login_response['stage_data'] = $arStageData['stage_data'];
            }
        }

        return $login_response;
    }

    protected function getAuthInfo($mobile_no, $type){
        $info = DB::table('members as a')
            ->select('a.id', 'a.username','a.email', 'a.mobile_no', 'a.password', 'a.user_type', 'b.model_key')
            ->join('models as b', 'b.id', '=', 'a.model_id')
            ->where('a.mobile_no', $mobile_no)
            ->where('a.is_active', 1)
            ->where('a.can_login', 1)
            ->where('a.user_type', '!=', 0)
            ->where('a.user_type', $type)
            ->first();
        return $info;
    }

    protected function makeToken($member_id, $client, $member_type){
        $token = hash('sha256',str_random(20),false);
        //Set expire previous token
        DB::table('member_tokens')
            ->where(['member_id'=> $member_id])
            ->update(['is_expire' => 1]);

        DB::table('member_tokens')->insert(
            [
                'member_id' => $member_id,
                'member_type' => $member_type,
                'token'     => $token,
                'client'    => $client,
                'expire_at' => date("Y-m-d H:i:s", strtotime ("+180 day"))
            ]
        );
        return $token;
    }

    private function saveDeviceId($device_id, $member_id){
        $device = $this->findOrThrowException($member_id);
        if (is_null($device)) {
            $device = new DeviceRegistration();
        }
        $device->device_id  = $device_id;
        $device->member_id  = $member_id;
        if ($device->save()) {
            return true;
        }
        return false;
    }

    public function findOrThrowException($member_id)
    {
        return DeviceRegistration::where('member_id', $member_id)->first();
    }

    protected function validationLoginRequest($inputs){
        $validator = Validator::make($inputs, [
            'mobile_no' => 'required',
            'password' => 'required',
            'user_type' => 'required|numeric|between:1,3'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    /*
     *  Passenger registration
     */

    public function postPassengerRegister(Request $request)
    {
        $inputs = Input::json()->all();

        if(!$this->validationPassengerRegisterRequest($inputs)){
            $this->_errors['success'] = false;
            $this->_errors['msg'] = 'Failed to register';
            return response($this->_errors, 200);
        }

        //Make unique activation code
        $activation_code = get_unique_numeric_code(5);
        $member_id = $this->member->create($inputs, $user_type = 1, $model_id = 2, $role_id = 2, $activation_code);
        if ($member_id > 0) {
            $passenger_id = $this->passenger->register($inputs, $member_id);
            if ($passenger_id > 0) {
                //Send sms
                if ($activation_code != '') {
                    send_sms($inputs['mobile_no'], "Your activation code is ".$activation_code);
                }
                //Save profile progress stage
                save_profile_progress_stage($member_id, "passenger_register");

                $this->response['success'] = true;
                $this->response['msg'] = 'Successfully registered';
                return response($this->response, 200);
            }
        }

        $this->response['success'] = false;
        $this->response['msg'] = 'Failed to registered';
        return response($this->response, 200);
    }

    protected function validationPassengerRegisterRequest($inputs){

        $validator = Validator::make($inputs, [
            'first_name'    => 'required|min:3|max:50',
            'last_name'     => 'required|min:3|max:50',
            'email'         => 'email',
            'mobile_no'     => 'required|unique:members,mobile_no,NULL,id,user_type,1|min:11|max:11',
            'password'      => 'required|min:6|max:60',
            'language'      => 'required|numeric',
            'gender'        => 'required|numeric',
            'zone_id'       => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    /*
     *  Driver registration
     */

    public function postDriverRegister(Request $request)
    {
        $inputs = Input::json()->all();

        if(!$this->validationDriverRegisterRequest($inputs)){
            $this->_errors['success'] = false;
            $this->_errors['msg'] = 'Failed to register';
            return response($this->_errors, 200);
        }

        //Make unique activation code
        $activation_code = get_unique_numeric_code(5);

        $member_id = $this->member->create($inputs, $user_type = 2, $model_id = 3, $role_id = 3, $activation_code);
        if ($member_id > 0) {
            $driver_id = $this->driver->register($inputs, $member_id);
            if ($driver_id > 0) {
                //Send sms
                if ($activation_code != '') {
                    send_sms($inputs['mobile_no'], "Your activation code is ".$activation_code);
                }
                //Save profile progress stage
                save_profile_progress_stage($member_id, "driver_register");

                $this->response['success'] = true;
                $this->response['msg'] = 'Successfully registered';
                return response($this->response, 200);
            }
        }

        $this->response['success'] = false;
        $this->response['msg'] = 'Failed to registered';
        return response($this->response, 200);
    }

    protected function validationDriverRegisterRequest($inputs){

        $validator = Validator::make($inputs, [
            'first_name'            => 'required|min:3|max:50',
            'last_name'             => 'required|min:3|max:50',
            'email'                 => 'email',
            'mobile_no'             => 'required|unique:members,mobile_no,NULL,id,user_type,2|min:11|max:11',
            'password'              => 'required|min:6|max:60',
            'language'              => 'required|numeric',
            'gender'                => 'required|numeric',
            'vehicle_category_id'   => 'required|numeric',
            'zone_id'               => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    /*
     *  Merchant registration
     */

    public function postMerchantRegister(Request $request)
    {
        $inputs = Input::json()->all();

        if(!$this->validationMerchantRegisterRequest($inputs)){
            $this->_errors['success'] = false;
            $this->_errors['msg'] = 'Failed to register';
            return response($this->_errors, 200);
        }

        //Make unique activation code
        $activation_code = get_unique_numeric_code(5);

        $member_id = $this->member->create($inputs, $user_type = 3, $model_id = 4, $role_id = 4, $activation_code);
        if ($member_id > 0) {
            $merchant_id = $this->merchant->register($inputs, $member_id);
            if ($merchant_id > 0) {
                //Send sms
                if ($activation_code != '') {
                    send_sms($inputs['mobile_no'], "Your activation code is ".$activation_code);
                }

                $this->response['success'] = true;
                $this->response['msg'] = 'Successfully registered';
                return response($this->response, 200);
            }
        }

        $this->response['success'] = false;
        $this->response['msg'] = 'Failed to registered';
        return response($this->response, 200);
    }

    protected function validationMerchantRegisterRequest($inputs){

        $validator = Validator::make($inputs, [
            'first_name'    => 'required|min:3|max:50',
            'last_name'     => 'required|min:3|max:50',
            'email'         => 'email',
            'mobile_no'     => 'required|unique:members,mobile_no,NULL,id,user_type,3|min:11|max:11',
            'password'      => 'required|min:6|max:60',
            'language'      => 'required|numeric',
            'gender'        => 'required|numeric',
            'zone_id'       => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }


    /*
     *  All service user logout
     */

    public function getLogout()
    {
        //
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
                send_sms($info->mobile_no, "Your password has been changed successfully");

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
    
    /**
     * @return string
     */
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
                'user_type' => $inputs['user_type']
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
                            'token'         => $token,
                            'token_expire'  => date("Y-m-d H:i:s", strtotime("+12 hours"))
                        ]
                    ]);
                 //Send sms
                $mobile = substr($info->mobile_no,0, 3).'*****'.substr($info->mobile_no, -3);
                send_sms($info->mobile_no, "Your password verification token is ".$token);

                $this->response['success'] = true;
                $this->response['code'] = '200';
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
            'user_type'  => 'required|numeric|between:1,3'
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
                ->update(['password' => bcrypt($inputs['new_password']), 'is_active' => 1, 'can_login' => 1]);

            //Send sms
            //send_sms($info->mobile_no, "Your password has been changed, your new password is ".$new_password);

            //$mobile = substr($info->mobile_no,0, 3).'*****'.substr($info->mobile_no, -3);

            $auth_info = DB::table('members as a')
                ->select('a.id', 'a.username','a.email', 'a.mobile_no', 'a.user_type', 'b.model_key')
                ->join('models as b', 'b.id', '=', 'a.model_id')
                ->where(['a.id' => $info->member_id])
                ->where('a.user_type', '!=', 0)
                ->first();
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
                'token.exists'=>'Provided token is invalid'
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

    public function postActivateAccountByCode(Request $request)
    {
        $client = $request->header('user-agent');
        $inputs = Input::json()->all();

        if(!$this->validationActivateAccountByCodeRequest($inputs)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = $this->getErrorAsString();
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }

        $auth_info = DB::table('members as a')
            ->select('a.id', 'a.username','a.email', 'a.mobile_no', 'a.password', 'a.user_type', 'b.model_key')
            ->join('models as b', 'b.id', '=', 'a.model_id')
            ->where('a.activation_code', $inputs['activation_code'])
            ->where('a.user_type', '!=', 0)
            ->first();

        if (!empty($auth_info)) {
            $member = Member::find($auth_info->id);
            $member->is_active          = 1;
            $member->can_login          = 1;
            $member->activation_code    = '';
            if ($member->save()) {
                //Make and save token to DB
                $token = $this->makeToken($auth_info->id, $client, $auth_info->user_type);

                $this->response['success'] = true;
                $this->response['token'] = $token;
                $this->response['member_type'] = $auth_info->user_type;
                $this->response['user_name'] = $auth_info->username;
                $this->response['email'] = $auth_info->email;
                $this->response['mobile_no'] = $auth_info->mobile_no;
                $this->response['member_id'] = $auth_info->id;


                $user_id = 0;
                $user_info = $this->{$auth_info->model_key}->getUserDetails($auth_info->id);
                if ( !empty($user_info)) {
                    $user_id = $user_info->id;
                    $this->response['first_name'] = $user_info->first_name;
                    $this->response['last_name'] = $user_info->last_name;
                    $this->response['user_id'] = $user_info->id;

                    //Send sms
                    $sms_text = "You account successfully verified";
                    if ($auth_info->user_type == 1) {
                        $sms_text.=", your referral code is {$user_info->referral_code}";
                        $this->response['referral_code'] = $user_info->referral_code;
                    }
                    send_sms($auth_info->mobile_no, $sms_text);

                    //Save device id
                    if (isset($inputs['device_id']) && $inputs['device_id'] != '') {
                        $this->saveDeviceId($inputs['device_id'], $auth_info->id);
                    }
                }

                if ($user_id > 0) {
                    $details = $this->{$auth_info->model_key}->details($auth_info->id, $user_id);
                    if ( !empty($user_info)) {
                        $this->response['details'] = $details['details'];
                    }
                }

                return response($this->response);
            }
        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = "Account verification failed";
        $this->response['error'] = get_error_response(200,  "Account verification failed");
        return response($this->response, 200);
    }

    protected function validationActivateAccountByCodeRequest($inputs){

        $validator = Validator::make($inputs, [
            'activation_code'  => 'required|exists:members'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
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

        $info = DB::table('members')
            ->select('id', 'mobile_no', 'password', 'activation_code')
            ->where('mobile_no', $inputs['mobile_no'])
            ->where('user_type', $inputs['user_type'])
            ->first();
        //If data is found by this mobile number and user type
        if (! empty($info)) {

            $user_data = DB::table('members as m')
                ->select('m.id', 'm.password', 'u.gender', 'u.first_name', 'u.last_name')
                ->leftJoin(get_user_table_by_user_type($inputs['user_type']). ' as u','u.member_id', '=', 'm.id')
                ->where('m.id', $info->id)
                ->first();

            //If some field is missing in users table(passenger, driver, merchant)

            if ('' == $user_data->password || '' == $user_data->first_name || '' == $user_data->last_name || '' === $user_data->gender) {
                $signin = false;
                $signup = false;
                $signup_data = true;

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

            }
            //If any field is equal to empty then sign in will be true , that is already assign

        // If data is not found by this mobile number and user type, then go to the signup
        } else {
            $signin = false;
            $signup = true;
            $this->makeVerifyMobileNumberCode($inputs['mobile_no'], $inputs['user_type']);
        }

        $this->response['signin'] = $signin;
        $this->response['signup'] = $signup;
        $this->response['signup_data'] = $signup_data;
        $this->response['authentication_code'] = $authentication_code;
        $this->response['code'] = '200';
        $this->response['message'] = "";
        $this->response['error'] = "";
        return response($this->response, 200);
    }

    protected function validationCheckMobileRequest($inputs){

        $validator = Validator::make($inputs, [
            'mobile_no' => 'required|min:11|max:11',
            'user_type' => 'required|numeric|between:1,3'
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
                'created_at'        => date('Y-m-d H:i:s')
                //'expire_at'         => date('Y-m-d H:i:s')
            ]
        );

        //Send sms
        if ($code != '') {
            send_sms($mobile_no, "Your verification code is ".$code);
            return true;
        }

        return;
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
            $this->response['error'] = "";

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

    protected function insertToMemberTable($data_array, $role_id)
    {
        $id = DB::table('members')->insertGetId($data_array);
        if ($id > 0) {
            DB::table('role_member')->insert([ 'role_id' => $role_id, 'member_id' => $id ]);
            return true;
        }
        return false;
    }

    //Save user data when scroll registraion in 3rd page
    public function postSaveProfileData(Request $request)
    {
        $client = $request->header('user-agent');
        $inputs = Input::json()->all();
        $inputs['language'] = 1;
        $inputs['zone_id'] = 322;

        if(!$this->validationSaveProfileDataRequest($inputs)){
            $this->_errors['success'] = false;
            $this->_errors['msg'] = 'Failed to save profile data';
            return response($this->_errors, 200);
        }

        $auth_info = DB::table('members as a')
            ->select('a.id', 'a.username','a.email', 'a.mobile_no', 'a.user_type', 'b.model_key')
            ->join('models as b', 'b.id', '=', 'a.model_id')
            ->where(['a.mobile_no' => $inputs['mobile_no'], 'a.user_type' => $inputs['user_type'], 'a.activation_code' => $inputs['authentication_code']])
            ->where('a.user_type', '!=', 0)
            ->first();

        if (!empty($auth_info)) {
            $member = Member::find($auth_info->id);
            $member->password       = bcrypt($inputs['password']);
            $member->is_active      = 1;
            $member->can_login      = 1;
            $member->activation_code      = '';

            if ($member->save()) {
                //Save user profile data data
                $this->{$auth_info->model_key}->register($inputs, $auth_info->id);

                $profile_progress = "driver_register";

                //Send sms
                $sms_text = "You are successfully registered";
                if ($auth_info->user_type == 1) {
                    $passenger = Passenger::where('member_id', $auth_info->id)->first();
                    if (isset($passenger->referral_code) && $passenger->referral_code != '') {
                        $sms_text.=", your referral code is {$passenger->referral_code}";
                    }
                    $profile_progress = "passenger_register";
                }
                send_sms($auth_info->mobile_no, $sms_text);

                // Save profile progress
                save_profile_progress_stage($auth_info->id, $profile_progress);

                $device_id = '';
                if (isset($inputs['device_id'])) {
                    $device_id = $inputs['device_id'];
                }
                return response($this->prepareUserLoginResponse($auth_info, $client, $device_id) , 200);
            }
        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = "Unable to save profile data";
        $this->response['error'] = get_error_response(200,  "Unable to save profile data");
        return response($this->response, 200);
    }

    protected function validationSaveProfileDataRequest($inputs){

        $validator = Validator::make($inputs, [
            'mobile_no'     => 'required|exists:members,mobile_no,user_type,' . $inputs['user_type'] . '|min:11|max:11',
            'user_type'     => 'required|numeric',
            'first_name'    => 'required|min:3|max:50',
            'last_name'     => 'required|min:3|max:50',
            'email'         => 'email',
            'password'      => 'required|min:6|max:60',
            'gender'        => 'required|numeric',
            'authentication_code'        => 'required',
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function postVerifyAccessToken(Request $request)
    {
        $client = $request->header('user-agent');
        $inputs = Input::json()->all();

        if(!$this->validationVerifyAccessTokenRequest($inputs)){
            $this->_errors['success'] = false;
            $this->_errors['msg'] = 'Invalid request';
            return response($this->_errors, 200);
        }

        $token_info = DB::table('member_tokens as mt')
            ->join('members as m', 'm.id', '=', 'mt.member_id')
            ->where(['m.is_active' => 1, 'm.can_login' => 1, 'm.status' => 1])
            ->where(['mt.token' => $inputs['token'], 'mt.client' => $client])
            ->where('mt.expire_at', '>', date("Y-m-d H:i:s"))
            ->where('mt.is_expire', 0)
            ->orderBy('mt.id', 'desc')
            ->first();

        if (! empty($token_info)) {
            $this->response['success'] = true;
            $this->response['code'] = '200';
            $this->response['message'] = "Access token is okay";
            $this->response['error'] = get_error_response(200,  "Access token is okay");
            return response( $this->response, 200);
        }

        $this->response['success'] = false;
        $this->response['code'] = '200';
        $this->response['message'] = "Invalid access token";
        $this->response['error'] = get_error_response(200,  "Invalid access token");
        return response($this->response, 200);
    }

    protected function validationVerifyAccessTokenRequest($inputs){

        $validator = Validator::make($inputs, [
            'token'        => 'required'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function postUpdateDeviceId(Request $request)
    {
        $inputs = Input::json()->all();

        if(!$this->validationUpdateDeviceIdRequest($inputs)){
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = 'Invalid request';
            $this->response['error'] = get_error_response(200,  $this->getErrorAsString());
            return response($this->response, 200);
        }

        if ($this->saveDeviceId($inputs['device_id'], $inputs['member_id'])) {
            $this->response['success'] = true;
            $this->response['code'] = '200';
            $this->response['message'] = "Successfully updated device id";
        } else {
            $this->response['success'] = false;
            $this->response['code'] = '200';
            $this->response['message'] = "Unable to update device ID";
            $this->response['error'] = get_error_response(200,  "Unable to update device ID");
        }
        
        api_logs("update-device-id", "Ezzyr Driver", "M_".$inputs['member_id'], json_encode($inputs), json_encode($this->response), "asdad", 0);

        return response( $this->response, 200);
    }

    protected function validationUpdateDeviceIdRequest($inputs){

        $validator = Validator::make($inputs, [
            'device_id'        => 'required',
            'member_id'        => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }
    
}
