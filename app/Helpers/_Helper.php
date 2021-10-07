<?php

use App\DB\Admin\Plans;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB as DB;
use \App\DB\Admin\AdminUser;
use \App\DB\Admin\Agent;
use \App\DB\Admin\TrackingDetails;
use \App\DB\Admin\TrackingDetailsSummary;
use \App\DB\Admin\Delivery;
//use Excel;

/**
 * Created by PhpStorm.
 * user: MAMUN AHMED
 * Date: 25-Aug-15
 * Time: 12:50 AM
 */
//To create new auto load file must be register to composer.json file
//And then RUN THIS COMMAND From Command prompt : composer dump-autoload

if (!function_exists('object_to_array')) {
    /**
     * Helper to return the object as array
     *
     * @return mixed
     */
    function object_to_array($object) {
        return (array) $object;
    }
}
if (!function_exists('array_to_object')) {
    /**
     * Helper to return the array as object
     *
     * @return mixed
     */
    function array_to_object($array) {
        return (object) $array;
    }
}

if (!function_exists('get_logged_user_id')) {
    /**
     * Helper to return the current login user id
     *
     * @return mixed
     */
    function get_logged_user_id()
    {
        if (Auth::user()) {
            $user_session = Auth::user();
            return $user_session->id;
        }
    }
}

if (!function_exists('get_logged_user_type')) {
    /**
     * Helper to return the current login user id
     *
     * @return mixed
     */
    function get_logged_user_type()
    {
        if (Auth::user()) {
            $user_session = Auth::user();
            return $user_session->user_type;
        }
    }
}

if (!function_exists('get_user_profile_pic')) {
    /**
     * Helper to return the current login user id
     *
     * @return mixed
     */

    function get_user_profile_pic()
    {
        if (Auth::user()) {
            return session('profile_pic');
        }
    }
}

if (!function_exists('get_logged_user_name')) {
    /**
     * Helper to return the current login user id
     *
     * @return mixed
     */
    function get_logged_user_name()
    {
        if (Auth::user()) {
            $user = '';
            if (session('user_type') == '0') {
                $user = AdminUser::where('member_id', get_logged_user_id())->first();

            } else if (session('user_type') == '4') {
                $user = Agent::where('member_id', get_logged_user_id())->first();
            }

            if ($user == '') {
                return '';
            }

            return $user->first_name." ".$user->last_name;
        }
    }
}

if (!function_exists('get_logged_user_email')) {
    /**
     * Helper to return the current login user id
     *
     * @return mixed
     */
    function get_logged_user_email()
    {
        if (Auth::user()) {
            $user_session = Auth::user();
            return $user_session->email;
        }
    }
}

/**
 * Backend menu active
 * @param $path
 * @param string $active
 * @return string
 */
if (!function_exists('setActive')) {
    function setActive($path, $active = 'm-menu__item--active m-menu__item--expanded m-menu__item--open')
    {

        if (is_array($path)) {

            foreach ($path as $k => $v) {
                $path[$k] = $v;
            }
        } else {
            $path = $path;
        }

        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }
}

if (!function_exists('get_current_bd_time')) {

    function get_current_bd_time() {
        date_default_timezone_set('Asia/Dhaka');
        return date('Y-m-d H:i:s');
    }

}

if (!function_exists('get_current_time_zone')) {

    function get_current_time_zone() {
        $dtz = new DateTimeZone('Asia/Dhaka');
        $time_in_sofia = new DateTime('now', $dtz);
        $offset = $dtz->getOffset($time_in_sofia) / 3600;
        return "GMT" . ($offset < 0 ? $offset : "+" . $offset);
    }

}

if (!function_exists('format_paginator')) {

    function format_paginator($data)
    {
        return [
            'total' => $data->total(),
            'currentPage' => $data->currentPage(),
            'lastPage' => $data->lastPage(),
            'perPage' => $data->perPage(),
            'hasMorePages' => $data->hasMorePages(),
            'nextPageUrl' => $data->nextPageUrl(),
        ];
    }
}

if(!function_exists('convert_number_to_words')){
    function convert_number_to_words($number) {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'first',
            2                   => 'second',
            3                   => 'third',
            4                   => 'fourth',
            5                   => 'fifth',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}

if (!function_exists('get_user_table_by_member_id')) {

    function get_user_table_by_member_id($member_id)
    {
        $table ="";
        $member = DB::table('members as m')
            ->select('mod.model_key')
            ->join('models as mod','mod.id', '=', 'm.model_id')
            ->where('m.id', $member_id)
            ->first();

        if (empty($member)) {
            return $table;
        }

        switch ($member->model_key){
            case 'admin':
                $table = 'admin_users';
                break;
            case 'passenger':
                $table = 'passengers';
                break;
            case 'driver':
                $table = 'drivers';
                break;
            case 'merchant':
                $table = 'merchants';
                break;
        }
        return $table.' as u';
    }
}

if (!function_exists('get_user_table_by_user_type')) {

    function get_user_table_by_user_type($user_type)
    {
        $table ="";

        switch ($user_type){
            case '0':
                $table = 'admin_users';
                break;
            case '1':
                $table = 'riders';
                break;
            case '2':
                $table = 'merchants';
                break;
            case '3':
                $table = 'merchants';
                break;
            case '4':
                $table = 'agents';
                break;
        }

        return $table;
    }
}

if (!function_exists('get_member_id_by_driver_id')) {

    function get_member_id_by_driver_id($driver_id)
    {
        $driver = DB::table('drivers as d')
            ->select('d.member_id')
            ->join('members as m','m.id', '=', 'd.member_id')
            ->where('d.id', $driver_id)
            ->first();
        return isset($driver->member_id) ? $driver->member_id: 0;
    }
}

if (!function_exists('get_member_id_by_passenger_id')) {

    function get_member_id_by_passenger_id($passenger_id)
    {
        $passenger = DB::table('passengers as p')
            ->select('p.member_id')
            ->join('members as m','m.id', '=', 'p.member_id')
            ->where('p.id', $passenger_id)
            ->first();
        return isset($passenger->member_id) ? $passenger->member_id: 0;
    }
}

if (!function_exists('get_passenger_id_by_member_id')) {

    function get_passenger_id_by_member_id($member_id)
    {
        $passenger = DB::table('passengers')
            ->select('id')
            ->where('member_id', $member_id)
            ->first();
        return isset($passenger->id) ? $passenger->id: 0;
    }
}

if (!function_exists('get_passenger_id_by_trip_id')) {

    function get_passenger_id_by_trip_id($trip_id)
    {
        $trip = DB::table('trips')
            ->select('passenger_id')
            ->where('id', $trip_id)
            ->first();
        return isset($trip->passenger_id) ? $trip->passenger_id: 0;
    }
}

if (!function_exists('get_driver_id_by_member_id')) {

    function get_driver_id_by_member_id($member_id)
    {
        $driver = DB::table('drivers')
            ->select('id')
            ->where('member_id', $member_id)
            ->first();
        return isset($driver->id) ? $driver->id: 0;
    }
}

if (!function_exists('get_merchant_id_by_member_id')) {

    function get_merchant_id_by_member_id($member_id)
    {
        $merchant = DB::table('merchants')
            ->select('id')
            ->where('member_id', $member_id)
            ->first();
        return isset($merchant->id) ? $merchant->id: 0;
    }
}

if (!function_exists('get_user_id_by_member_id')) {

    function get_user_id_by_member_id($member_id)
    {
        $member = DB::table('members')
            ->select('id', 'model_id', 'user_type')
            ->where('id', $member_id)
            ->first();

        if ( empty($member)) {
            return 0;
        }

        if ($member->model_id == 1 && $member->user_type == 0) {
            $admin = DB::table('admin_users')
                ->select('id')
                ->where('member_id', $member_id)
                ->first();
            return isset($admin->id) ? $admin->id: 0;

        } else if ($member->model_id == 2 && $member->user_type == 1) {
            $passenger = DB::table('passengers')
                ->select('id')
                ->where('member_id', $member_id)
                ->first();
            return isset($passenger->id) ? $passenger->id: 0;

        } else if ($member->model_id == 3 && $member->user_type == 2) {
            $driver = DB::table('drivers')
                ->select('id')
                ->where('member_id', $member_id)
                ->first();
            return isset($driver->id) ? $driver->id: 0;

        } else if ($member->model_id == 4 && $member->user_type == 3) {
            $merchant = DB::table('merchants')
                ->select('id')
                ->where('member_id', $member_id)
                ->first();
            return isset($merchant->id) ? $merchant->id: 0;
        }
    }
}

if (!function_exists('get_full_name_by_member_id')) {

    function get_full_name_by_member_id($member_id)
    {
        $member = DB::table('members')
            ->select('id', 'model_id', 'user_type')
            ->where('id', $member_id)
            ->first();

        if ( empty($member)) {
            return 0;
        }

        if ($member->model_id == 1 && $member->user_type == 0) {
            $admin = DB::table('admin_users')
                ->select('id', 'first_name', 'last_name')
                ->where('member_id', $member_id)
                ->first();
            return isset($admin->id) ? $admin->first_name ." ". $admin->last_name : '';

        } else if ($member->model_id == 2 && $member->user_type == 1) {
            $passenger = DB::table('passengers')
                ->select('id', 'first_name', 'last_name')
                ->where('member_id', $member_id)
                ->first();
            return isset($passenger->id) ? $passenger->first_name ." ". $passenger->last_name : '';

        } else if ($member->model_id == 3 && $member->user_type == 2) {
            $driver = DB::table('drivers')
                ->select('id', 'first_name', 'last_name')
                ->where('member_id', $member_id)
                ->first();
            return isset($driver->id) ? $driver->first_name ." ". $driver->last_name : '';

        } else if ($member->model_id == 4 && $member->user_type == 3) {
            $merchant = DB::table('merchants')
                ->select('id', 'first_name', 'last_name')
                ->where('member_id', $member_id)
                ->first();
            return isset($merchant->id) ? $merchant->first_name ." ". $merchant->last_name : '';
        }
    }
}

if (!function_exists('get_user_type_by_member_id')) {

    function get_user_type_by_member_id($member_id)
    {
        $member = DB::table('members')
            ->select('user_type')
            ->where('id', $member_id)
            ->first();
        return isset($member->user_type) ? $member->user_type: '';
    }
}

if (!function_exists('get_device_id_by_member_id')) {

    function get_device_id_by_member_id($member_id)
    {
        $member = DB::table('device_registration_number')
            ->select('device_id')
            ->where('member_id', $member_id)
            ->orderBy("id","DESC")
            ->first();
        return isset($member->device_id) ? $member->device_id: '';
    }
}

if (!function_exists('save_notification')) {

    function save_notification($member_id, $body, $notofication_id, $model_id)
    {
        $id = DB::table('notifications')->insertGetId(
            [
                'body'      => $body,
                'notification_type_id' => $notofication_id,
                'model_id'  => $model_id,
                'member_id' => $member_id,
                'status'    => 1
            ]
        );

        if ($id > 0) {
            return $id;
        }
        return false;
    }
}


if (!function_exists('get_error_response')) {

    function get_error_response($code, $reason, $errors = [],  $error_as_string = '', $description = '')
    {
        if ($error_as_string == '') {
            $error_as_string = $reason;
        }

        if ($description == '') {
            $description = $reason;
        }

        return [
            'code'          => $code,
            'errors'        => $errors,
            'error_as_string'  => $error_as_string,
            'reason'        => $reason,
            'description'   => $description,
            'error_code'    => $code,
            'link'          => ''
        ];
    }
}


if (!function_exists('save_profile_progress_stage')) {

    function save_profile_progress_stage($member_id, $progress_stage_string)
    {
        $profile_progress_id = get_profile_progress_id($progress_stage_string);

        if ($member_id < 1 || $profile_progress_id < 1) {
            return false;
        }

        $result = DB::table('member_profile_progress')
            ->select('id')
            ->where('member_id', $member_id)
            ->where('profile_progress_id', $profile_progress_id)
            ->first();

        if ( !empty($result)) {
            return;
        }

        $id = DB::table('member_profile_progress')->insertGetId(
            [
                'profile_progress_id'   => $profile_progress_id,
                'member_id'             => $member_id,
                'created_at'            => date('Y-m-d H:i:s'),
            ]
        );

        if ($id > 0) {
            return true;
        }
        return false;
    }
}

if (!function_exists('get_profile_progress_id')) {

    function get_profile_progress_id($progress_stage_string)
    {

        if ($progress_stage_string == '') {
            return 0;
        }

        //This array value are math from database 'profile_progress' table
        $items = [
            'passenger_register'          => 1,
            'passenger_profile_image'     => 2,
            'passenger_national_id'       => 3,
            'driver_register'             => 4,
            'driver_profile_image'        => 5,
            'driver_license'              => 6,
            'driver_national_id'          => 7
        ];

        if (array_key_exists($progress_stage_string, $items)) {
            return $items[$progress_stage_string];
        } else {
            return 0;
        }

    }
}

if (!function_exists('get_gcm_api_key')) {

    function get_gcm_api_key($desired_key = 'rider_key')
    {
        $api_key = [
            'rider_key' => 'AIzaSyB-9oERHmu3Q-K1P2Ps87kFD9FCx8zLjos',
            'driver_key' => 'AIzaSyD0OshN19H-9uEz8HzKgnJmmC2gjSbKD7g'
        ];

        return $api_key[$desired_key];
    }
}

if (!function_exists('send_push_notification')) {

    function send_push_notification($access_key, $device_ids, $message, $title = 'Notification from ezzyr')
    {
        if (!is_array($device_ids)) {
            $device_ids = [$device_ids];
        }

        if (!empty($device_ids)) {

            $msg = [
                'message'       => $message,
                'title'         => $title,
                'subtitle'      => 'www.ezzyr.com',
                'tickerText'    => '',
                'vibrate'       => 1,
                'sound'         => 1,
                'largeIcon'     => 'large_icon',
                'smallIcon'     => 'small_icon'
            ];

            $fields = [
                'registration_ids' => $device_ids,
                'data' => $msg
            ];

            $headers = [
                'Authorization: key=' . get_gcm_api_key($access_key),
                'Content-Type: application/json'
            ];
            //SMS CURL
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch );
            //Get gcm status
            $gcm_return_data = json_decode($result,true);

            if ($gcm_return_data['success'] == 1) {
                api_logs("send_push_notification", "Helper Method", 0, json_encode($fields), json_encode($gcm_return_data), "success", 0);
                return true;
            } else {
                api_logs("send_push_notification", "Helper Method", 0, json_encode($fields), json_encode($gcm_return_data), "fail", 0);
                return false;
            }

        }

        return false;
    }
}

if (!function_exists('send_sms')) {
    function send_sms($mobile_no, $message_text){
        return true;
//        $api_key = "C20041405d2c72af545396.86148072";
//        $type = "text";
//        $sender_id = "8804445629108";
//
//        if ($message_text == '' || empty($mobile_no)) {
//            return false;
//        }
//
//        $message_text = urlencode($message_text);
//        $api_url = "http://esms.dianahost.com/smsapi?api_key={$api_key}&type={$type}&contacts={$mobile_no}&senderid={$sender_id}&msg={$message_text}";
//
//        $ch = curl_init();
//        curl_setopt( $ch,CURLOPT_URL, $api_url);
//        curl_setopt( $ch,CURLOPT_HTTPHEADER, [] );
//        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
//        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
//        $response = curl_exec($ch);
//        curl_close($ch);
//        if (strlen(str_replace("SMS SUBMITTED: ID -", "", $response)) > 4) {
//            return true;
//        }
//        return false;
    }
}

if (!function_exists('is_driver_in_offline')) {

    function is_driver_in_offline($driver_id)
    {
        $member = DB::table('drivers')
            ->select('is_offline')
            ->where('id', $driver_id)
            ->first();
        if (isset($member->is_offline) && $member->is_offline == 1) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_already_respond')) {

    function is_already_respond($request_id)
    {
        if ($request_id < 1)
            return false;

        $data = DB::table('trip_request_response')
            ->select('driver_id')
            ->where('request_id', $request_id)
            ->first();
        if (isset($data->driver_id) && $data->driver_id > 0)
            return true;
        else
            return false;
    }
}

if (!function_exists('makeUniqueNumericCode')) {
    function makeUniqueNumericCode($length) {
        $con = '';
        $number = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];
        for ($i = 0; $i < $length; $i++) {
            $rand_value = rand(0, 9);
            $rand_number = $number[$rand_value];
            $con .= $rand_number;
        }
        return $con;
    }
}


if (!function_exists('get_generated_code')) {

    function get_generated_code($length) {
        $number = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

        for ($i = 0; $i < $length; $i++) {
            $rand_value = rand(0, 61);
            $rand_number = $number["$rand_value"];

            if (empty($con)) {
                $con = $rand_number;
            } else {
                $con = "$con" . "$rand_number";
            }
        }
        return $con;
    }
}

if (!function_exists('isExistCodeInDb')) {
    function isExistCodeInDb($code) {
		
		try {
			DB::table('deliveries')->where('consignment_id', $code)->first();
			return false;
		}catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			return true;
		}
		/*
        $data = DB::table('deliveries')
            ->select('id')
            ->where('consignment_id', $code)
            ->get();
        if (empty($data)) {
            return true;
        }
        return false;
		*/
    }
}

if (!function_exists('getUniqueConsignmentId')) {
    function getUniqueConsignmentId($length) {
        while(true) {
            $code = get_generated_code($length);
            if (isExistCodeInDb($code)) {
                break;
            }
        }
        return $code;
    }
}

if (!function_exists('isExistNumberInDb')) {
    function isExistNumberInDb($code) {
        $data = DB::table('invoices')
            ->select('id')
            ->where('invoice_no', $code)
            ->get();
        if (empty($data)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getUniqueInvoiceNumber')) {
    function getUniqueInvoiceNumber($length) {
        while(true) {
            $code = get_generated_code($length);
            if (isExistNumberInDb($code)) {
                break;
            }
        }
        return $code;
    }
}

if (!function_exists('isExistMerchantCodeInDb')) {
    function isExistMerchantCodeInDb($code) {
        $data = DB::table('merchants')
            ->select('id')
            ->where('merchant_code', $code)
            ->get();
        if (empty($data)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getUniqueMerchantCode')) {
    function getUniqueMerchantCode($length) {
        while(true) {
            $code = get_generated_code($length);
            if (isExistMerchantCodeInDb($code)) {
                break;
            }
        }
        return $code;
    }
}

if (!function_exists('isExistPlanCodeInDb')) {
    function isExistPlanCodeInDb($code) {
        $data = DB::table('plans')
            ->select('id')
            ->where('plan_code', $code)
            ->get();
        if (empty($data)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getUniquePlanCode')) {
    function getUniquePlanCode($length) {
        while(true) {
            $code = get_generated_code($length);
            if (isExistPlanCodeInDb($code)) {
                break;
            }
        }
        return $code;
    }
}

if (!function_exists('isExistZoneCodeInDb')) {
    function isExistZoneCodeInDb($code) {
        $data = DB::table('courier_zones')
            ->select('id')
            ->where('zone_code', $code)
            ->get();
        if (empty($data)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getUniqueZoneCode')) {
    function getUniqueZoneCode($length) {
        while(true) {
            $code = get_generated_code($length);
            if (isExistZoneCodeInDb($code)) {
                break;
            }
        }
        return $code;
    }
}

if (!function_exists('isExistHubCodeInDb')) {
    function isExistHubCodeInDb($code) {
        $data = DB::table('hub')
            ->select('id')
            ->where('hub_code', $code)
            ->get();
        if (empty($data)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getUniqueHubCode')) {
    function getUniqueHubCode($length) {
        while(true) {
            $code = get_generated_code($length);
            if (isExistHubCodeInDb($code)) {
                break;
            }
        }
        return $code;
    }
}


if (!function_exists('get_settings')) {
    function get_settings() {
        return DB::table('settings')
            ->select('*')
            ->first();
    }
}
if (!function_exists('get_main_settings')) {
    function get_main_settings($keys) {

        $arKeys = explode(",", $keys);

        $response = [];
        foreach ($arKeys as $value) {
            $value = trim($value);
            $method = DB::table('settings_main')
                ->select('config_value')
                ->where(['config_key' => trim($value)])
                ->first();
            if(!empty($method))
            {
                $unserialize = @unserialize($method->config_value);
                if($unserialize === false) {
                     $response[$value] = $method->config_value;
                }
                else {
                    $response[$value] = @unserialize($method->config_value);
                }
            }
            else
            {
                $response[$value] = 'UNDEFINED';
            }
        }
        return $response;
    }
}

if (!function_exists('update_passenger_balance')) {
    function update_passenger_balance($passenger_id, $amount) {

        if (!is_numeric($passenger_id) || !is_numeric($amount)) {
            return false;
        }

        DB::table('passengers')
            ->where('id', $passenger_id)
            ->update(['balance_amount' => $amount]);

        return true;
    }
}

if (!function_exists('get_passenger_current_balance')) {
    function get_passenger_current_balance($passenger_id) {
        if (!is_numeric($passenger_id) || 1 > $passenger_id) {
            return false;
        }

        $data = DB::table('passengers')
            ->select('balance_amount')
            ->where('id', $passenger_id)
            ->first();
        if (isset($data->balance_amount)) {
            return $data->balance_amount;
        }

        return 0;
    }
}

if (!function_exists('update_driver_balance')) {
    function update_driver_balance($driver_id, $amount) {
        if ($driver_id == '' || $amount == '' || 1 > $amount) {
            return false;
        }

        if (!is_numeric($driver_id) || !is_numeric($amount)) {
            return false;
        }

        DB::table('drivers')
            ->where('id', $driver_id)
            ->update(['balance_amount' => $amount]);

        return true;
    }
}

if (!function_exists('get_driver_current_balance')) {
    function get_driver_current_balance($driver_id) {
        if (!is_numeric($driver_id) || 1 > $driver_id) {
            return false;
        }

        $data = DB::table('drivers')
            ->select('balance_amount')
            ->where('id', $driver_id)
            ->first();
        if (isset($data->balance_amount)) {
            return $data->balance_amount;
        }

        return 0;
    }
}


if (!function_exists('get_promo_code_rules')) {
    function get_promo_code_rules() {
        return [
            "flat_discount"         => "==",
            "percent_discount"      => "<=",
            "amount_discount"       => "<=",
            "max_trips"             => "<=",
            "max_date"              => "<=",
            "min_date"              => "<=",
            "start_time"            => ">=",
            "end_time"              => "<=",
        ];
    }
}

if (!function_exists('get_promo_code_rules_key')) {
    function get_promo_code_rules_key() {
        return [
            "flat_discount",
            "percent_discount",
            "amount_discount",
            "max_trips",
            "max_date",
            "min_date",
            "start_time",
            "end_time"
        ];
    }
}

if (!function_exists('format_paginator')) {

    function format_paginator($data)
    {
        return [
            'total' => $data->total(),
            'currentPage' => $data->currentPage(),
            'lastPage' => $data->lastPage(),
            'perPage' => $data->perPage(),
            'hasMorePages' => $data->hasMorePages(),
            'nextPageUrl' => $data->nextPageUrl()
        ];
    }
}

if (!function_exists('floor_by_nearest_five')) {

    function floor_by_nearest_five($amount)
    {
        return 5 * floor($amount / 5);
    }
}

if (!function_exists('get_current_week_start_end_date')) {

    function get_current_week_start_end_date()
    {
        date_default_timezone_set('Asia/Dhaka');
        $week = [];
        if(date('D') != 'Sat')
        {
            //Take the last saturday
            $week['start_date'] = date('Y-m-d',strtotime('last Saturday'));

        }else{
            $week['start_date'] = date('Y-m-d');
        }

        //Always next Friday

        if(date('D') != 'Fri')
        {
            $week['end_date'] = date('Y-m-d',strtotime('next Friday'));
        }else{
            $week['end_date'] = date('Y-m-d');
        }

        // Set plus 3 days with end week day
        $week['lock_date'] = date('Y-m-d', strtotime($week['end_date']. ' + 3 days'));

        return $week;
    }
}

if (!function_exists('get_currently_engage_driver_id')) {

    function get_currently_engage_driver_id()
    {
        $id_array = [];
        //Just response within 30 min but not started trip yet ...
        $respond_drivers = DB::table('trip_request_response')
            ->select('driver_id','is_cancel','is_cancelled_by_passenger')
            ->whereRaw('id IN 
                    (SELECT MAX(id) as id FROM trip_request_response
                        WHERE 
                            response_at >= DATE_SUB(NOW(),INTERVAL 30 MINUTE)
                        GROUP BY driver_id)')
            ->where(['status' => 0])
            ->havingRaw('is_cancel = 0 AND is_cancelled_by_passenger = 0')
            ->get();
        if (!empty($respond_drivers)) {
            foreach ($respond_drivers as $key => $respond_driver) {
                $id_array[] = $respond_driver->driver_id;
            }
        }

        //Riding now or started last trip at least 4 hours ago ...
        $riding_drivers = DB::table('trips')
            ->select('driver_id','is_cancel')
            ->whereRaw('id IN 
                    (SELECT MAX(id) as id FROM trips
                        WHERE 
                            started_at >= DATE_SUB(NOW(),INTERVAL 4 HOUR)
                        AND
                            status = 0
                        GROUP BY driver_id)')
            ->where(['status' => 0])
            ->havingRaw('is_cancel = 0')
            ->get();

        if (!empty($riding_drivers)) {
            foreach ($riding_drivers as $index => $riding_driver) {
                $id_array[] = $riding_driver->driver_id;
            }
        }

        //Driver whose car is inactive
        $riding_drivers = DB::select(DB::raw('SELECT 
            driver_id
        FROM
            driver_active_vehicle
        WHERE
            is_active = 0
                AND driver_id NOT IN (SELECT 
                    driver_id
                FROM
                    driver_active_vehicle
                WHERE
                    is_active = 1 GROUP BY driver_id)
        GROUP BY driver_id'));

        if (!empty($riding_drivers)) {
            foreach ($riding_drivers as $index => $riding_driver) {
                $id_array[] = $riding_driver->driver_id;
            }
        }

        return implode(',',array_unique($id_array));
    }
}

if (!function_exists('set_driver_block_status')) {

    function set_driver_block_status($driver_id, $status_val = 0)
    {
        $ql ="INSERT INTO driver_block_status 
                (driver_id, status) 
            VALUES({$driver_id}, {$status_val})
              ON DUPLICATE KEY UPDATE driver_id = {$driver_id}, status = {$status_val}";
        DB::statement($ql);
        return true;
    }
}

if (!function_exists('api_logs')) {

    function api_logs($api_url, $call_by_app, $call_by_member_id, $parameters, $response, $server_msg = "", $trip_request_id = 0)
    {
        $id = DB::table('api_logs')->insertGetId(
            [
                'api_url'   => $api_url,
                'call_by_app'             => $call_by_app,
                'call_by_member_id'       => $call_by_member_id,
                'parameters'              => $parameters,
                'response'                => $response,
                'server_msg'              => $server_msg,
                'trip_request_id'         => $trip_request_id,
                'created_at'              => date('Y-m-d H:i:s')
            ]
        );
        return true;
    }
}
/*trip request api log*/
if (!function_exists('api_logs')) {

    function api_logs($api_url, $call_by_app, $call_by_member_id, $parameters, $response, $server_msg = "", $trip_request_id = 0)
    {
        $id = DB::table('api_logs')->insertGetId(
            [
                'api_url'   => $api_url,
                'call_by_app'             => $call_by_app,
                'call_by_member_id'       => $call_by_member_id,
                'parameters'              => $parameters,
                'response'                => $response,
                'server_msg'              => $server_msg,
                'trip_request_id'         => $trip_request_id,
                'created_at'              => date('Y-m-d H:i:s')
            ]
        );
        return true;
    }
}
/*checkPromoType is referral or promo */
if (!function_exists('checkPromoType')) {

    function checkPromoType($promoCode)
    {
        $data = DB::table('promo_codes')
            ->select('key as codekey')
            ->distinct()->get();
        //prixt($data, 1);

        if(!empty($data))
        {
            foreach ($data as $value) {
                if (strpos($promoCode, $value->codekey) !== false) {
                    return true;
                }
            }
        }
    }
}

/*Rearrage data and check if data is Unserialize or not */
if (!function_exists('checkUnserialize')) {

    function dataRearrage($dataTripDetails, $usreType = 0)
    {
        if(empty($dataTripDetails))
        {
            return $dataTripDetails;
        }

        unset($dataTripDetails->base_fare);
        unset($dataTripDetails->unit_fare);
        unset($dataTripDetails->waiting_min);
        unset($dataTripDetails->wtng_min_charge);

        switch ($usreType) {
            case 1:
                unset($dataTripDetails->driver_object);
                $dataTripDetails->passenger_object = checkUnserialize($dataTripDetails->passenger_object);
                $dataTripDetails->trip_route = checkUnserialize($dataTripDetails->trip_route);
                break;
            case 2:
                unset($dataTripDetails->passenger_object);
                $dataTripDetails->driver_object = checkUnserialize($dataTripDetails->driver_object);
                $dataTripDetails->trip_route = checkUnserialize(base64_decode($dataTripDetails->trip_route));
                break;
            default:
                $dataTripDetails = null;
        }

        return $dataTripDetails;
    }
}

/*check if data is Unserialize or not */
if (!function_exists('checkUnserialize')) {

    function checkUnserialize($arData)
    {
        $unserialize = @unserialize($arData);
        if($unserialize === false) {
             $response = $arData;
        }
        else {
            $response = @unserialize($arData);
        }

        return $response;
    }
}

/*Split Promocode to codekey and  code */
if (!function_exists('splitPromoCode')) {

    function splitPromoCode($promoCode)
    {
        $data = DB::table('promo_codes')
            ->select('key as codekey')
            ->distinct()->get();
        //prixt($data, 1);
        $arPromoCode = array();
        if(!empty($data))
        {
            foreach ($data as $value) {
                if (strpos($promoCode, $value->codekey) !== false) {
                    $arPromoCode['code'] = str_replace($value->codekey,"",$promoCode);
                    $arPromoCode['codekey'] = $value->codekey;
                }
            }
        }
        //prixt($arPromoCode,1);
        return $arPromoCode;
    }
}

/*is_in_polygon */
if (!function_exists('is_in_polygon')) {
    function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $points_polygon = $points_polygon -1;

        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[j] - $vertices_y[$i]) + $vertices_x[$i]) ))
           $c = $c;
      }
      return  $c;
    }
}
/*is_in_polygon */
if (!function_exists('pointInPolygon')) {
    function pointInPolygon($polySides,$polyX,$polyY,$x,$y) {
        $j = $polySides-1;

        $oddNodes = 0;

        for ($i=0; $i<$polySides; $i++) {

            if ($polyY[$i]<$y && $polyY[$j]>=$y ||  $polyY[$j]<$y && $polyY[$i]>=$y)
            {
                if ($polyX[$i]+($y-$polyY[$i])/($polyY[$j]-$polyY[$i])*($polyX[$j]-$polyX[$i])<$x)
                {
                  $oddNodes=!$oddNodes;

                }
            }

            $j=$i;
        }

        return $oddNodes;

    }
}

/*Print+Exit = print */
if (!function_exists('prixt')) {

    function prixt($data, $exit = 0)
    {
        echo "<pre>";
        print_r($data);
        if($exit == 1)
        {
            exit;
        }
    }
}

if (!function_exists('truncate')) {

    function truncate($string, $length, $stopanywhere=false) {
        //truncates a string to a certain char length, stopping on a word if not specified otherwise.
        if (strlen($string) > $length) {
            //limit hit!
            $string = substr($string,0,($length -3));
            if ($stopanywhere) {
                //stop anywhere
                $string .= '...';
            } else{
                //stop on a word.
                $string = substr($string,0,strrpos($string,' ')).'...';
            }
        }
        return $string;
    }
}


if (!function_exists('date_validate')) {

    function date_validate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}


if (!function_exists('getLogedinAgentId')) {

    function getLogedinAgentId()
    {
        $user_id = get_logged_user_id();
        if ($user_id == '' || $user_id < 1) return '';
        $agent_user = Agent::where('member_id', get_logged_user_id())->first();
        if (! empty($agent_user)) {
           // if ($agent_user->parent_id <= 0) {
                return $agent_user->id;
           // }
        }
        return '';
    }
}

if (!function_exists('get_vehicle_reg_key')) {

    function get_vehicle_reg_key() {
        return $number = array("E" => "E", "DAW" => "DAW", "WUA" => "WUA", "FA" => "FA", "MA" => "MA", "THA" => "THA", "CAA" => "CAA", "U" => "U", "TA" => "TA", "MA" => "MA", "NA" => "NA", "AU" => "AU", "DA" => "DA", "TAW" => "TAW", "GA" => "GA", "KHA" => "KHA", "KA" => "KA", "BHA" => "BHA", "LA" => "LA", "HA" => "HA", "A" => "A", "RA" => "RA", "ZA" => "ZA", "DHA" => "DHA", "CHA" => "CHA", "GHA" => "GHA", "JHA" => "JHA", "SA" => "SA", "JA" => "JA", "BA" => "BA", "SHA" => "SHA", "PA" => "PA");
    }

}

if (!function_exists('userRolePermissionArray')) {
    function userRolePermissionArray() {
        $roles = DB::table('role_permissions as rp')
            ->select('rp.permissions')
            ->join('role_member as rm','rm.role_id', '=', 'rp.role_id')
            ->where('rm.member_id', get_logged_user_id())
            ->first();
        if (! empty($roles)) {
            return explode(",", $roles->permissions);
        }

        return [];
    }
}

if (!function_exists('hasRoleToThisUser')) {
    /**
     * Helper to return the current login user id
     *
     * @return mixed
     */
    function hasRoleToThisUser($user_id)
    {
        return DB::table('role_member')->where('member_id', $user_id)->value('role_id');
    }
}

if (!function_exists('hasAccessAbility')) {
    function hasAccessAbility($permission_slug, $permission_array) {
        $user_id = get_logged_user_id();
        if ($user_id == 1) return true;

        $role_id = hasRoleToThisUser($user_id);
        if ($role_id == 1) return true;

        if (! empty($permission_slug) && ! empty($permission_array)) {
            if (in_array($permission_slug, $permission_array)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('agent_has_company')) {
    function agent_has_company()
    {
        if (Auth::user()) {
            if (session('company_id') > 0) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('agentHasAdminAccess')) {
    function agentHasAdminAccess()
    {
        $agent = DB::table('agents')
            ->select('is_admin')
            ->where('id', getLogedinAgentId())
            ->first();
        if (!empty($agent)) {
            return $agent->is_admin == 1 ? true : false;
        }
        return false;
    }
}

if (!function_exists('get_agent_company_id')) {
    function get_agent_company_id()
    {
        if (! agent_has_company()) {
            return 0;
        }

        $agent = DB::table('agents')
            ->select('company_id')
            ->where('id', getLogedinAgentId())
            ->first();
        return isset($agent->company_id) ? $agent->company_id: 0;
    }
}


if (!function_exists('get_company_details')) {
    function get_company_details()
    {
        return DB::table('companies')
            ->select('*')
            ->where('id', get_agent_company_id())
            ->first();
    }
}

if (!function_exists('getLogedinAgentIdForDashboardReport')) {
    function getLogedinAgentIdForDashboardReport()
    {
        $user_id = get_logged_user_id();
        if ($user_id == '' || $user_id < 1) return '';
        $agent_user = Agent::where('member_id', get_logged_user_id())->first();
        if (! empty($agent_user)) {
            return $agent_user->id;
        }
        return '';
    }
}

if (!function_exists('get_image_host')) {
    function get_image_host($key = '')
    {
        $host = 'http://services.ezzyr.com/';
        $host_array = [
            'license'           => "{$host}resources/license/",
            'national_id'       => "{$host}resources/national_id/",
            'national_id_back'  => "{$host}resources/national_id_back/",
            'profile_image'     => "{$host}resources/profile_image/",
            'route_image'       => "{$host}resources/route_image/",
            'vehicle_type'      => "{$host}resources/vehicle_type/",
            'fitness'           => "{$host}resources/vehicle/fitness/",
            'insurance'         => "{$host}resources/vehicle/insurance/",
            'objection'         => "{$host}resources/vehicle/objection/",
            'registration'      => "{$host}resources/vehicle/registration/",
            'tax_token'         => "{$host}resources/vehicle/tax_token/",
            'company_logo'      => "{$host}resources/company_logo/",
            'push_notification' => "{$host}resources/push_notification/",
        ];

        if (array_key_exists($key, $host_array)) {
            $host =  $host_array[$key];
        }

        return $host;
    }
}

if (!function_exists('remote_file_exist')) {
    function remote_file_exist($file_url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $file_url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(curl_exec($ch) !== FALSE) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('member_unique_id_generator')) {
    function member_unique_id_generator($user_type, $mobile_no, $member_id = null)
    {
        if ($user_type == '' || strlen($mobile_no) < 11) {
            return;
        }

        if ($member_id) {
            $item = DB::table('members')
                ->select('unique_id')
                ->where('id', $member_id)
                ->first();
            if (! empty($item)) {
                if(strlen($item->unique_id) >= 13) {
                    return $item->unique_id;
                }
            }
        }

        switch ($user_type){
            case '2':
                $prefix = 'DR';
                break;
            case '3':
                $prefix = 'MR';
                break;
            case '4':
                $prefix = 'AG';
                break;
        }

        $first_part = $prefix . '-' . substr($mobile_no, 2, 3);
        $last_part = substr($mobile_no, 5, 6);
        $final_part = $first_part . '-' . $last_part;

        while(true) {
            $data = DB::table('members')
                ->select('id')
                ->where([ 'user_type' => $user_type, 'unique_id' => $final_part ])
                ->first();
            if (empty($data)) {
                return $final_part;
            }

            $last_part = ( $last_part == str_shuffle($last_part)) ? substr($last_part, 0, 3) . '789' : $last_part;
            $final_part = $first_part . '-' . str_shuffle($last_part);
        }
    }
}


if (!function_exists('send_email')) {
    function send_email($to, $subject = '(No subject)', $data = [], $body_file = '', $cc = '', $bcc = '')
    {
        if (empty($to) || $body_file == '') {
            return false;
        }

        $user = (object) [
            'from'      => 'info@ezzyr.com',
            'from_name' => 'Ezzyr support team !',
            'to'        => $to,
            'to_name'   => null,
            'subject'   => $subject,
            'reply_to'  => 'info@ezzyr.com',
            'cc'        => $cc,
            'bcc'       => $bcc,
        ];

        try {
            Mail::send("email-templates.{$body_file}", $data, function ($message) use ($user) {
                $message->from($user->from, $user->from_name);
                $message->to($user->to, $user->to_name);
                $message->subject($user->subject);
                $message->replyTo($user->reply_to, $name = null);

                if (isset($user->cc) && $user->cc != '') {
                    $message->cc($user->cc, $name = null);
                }

                if (isset($user->bcc) && $user->bcc != '') {
                    $message->bcc($user->cc, $name = null);
                }
            });

            return true;
        } catch (\Exception $e){
            print_r($e);exit;
        }
    }
}


if (!function_exists('send_raw_email')) {
    function send_raw_email($to, $body_text, $subject = '(No subject)',  $cc = '', $bcc = '')
    {
        if (empty($to) || empty($body_text)) {
            return false;
        }

        $user = (object) [
            'from'      => 'info@ezzyr.com',
            'from_name' => 'Ezzyr support team !',
            'to'        => $to,
            'to_name'   => null,
            'subject'   => $subject,
            'reply_to'  => 'info@ezzyr.com',
            'cc'        => $cc,
            'bcc'       => $bcc,
        ];

        try {
            Mail::raw($body_text, function ($message) use ($user) {
                $message->from($user->from, $user->from_name);
                $message->to($user->to, $user->to_name);
                $message->subject($user->subject);
                $message->replyTo($user->reply_to, $name = null);

                if (isset($user->cc) && $user->cc != '') {
                    $message->cc($user->cc, $name = null);
                }

                if (isset($user->bcc) && $user->bcc != '') {
                    $message->bcc($user->cc, $name = null);
                }
            });
            return true;
        } catch (\Exception $e){
            print_r($e);exit;
        }


    }
}

if (!function_exists('getBdDateTimeFormat')) {
    function getBdDateTimeFormat($mysql_format)
    {
        if (empty($mysql_format)) return "";
        return date( 'D M Y g:i a', strtotime($mysql_format));
    }
}

if (!function_exists('partnerCompaniesCategory')) {
    function partnerCompaniesCategory()
    {
        $category = [
            '1' => 'Resort',
            '2' => 'Hotel',
            '3' => 'Restaurant',
            '4' => 'Electronics',
            '5' => 'Event Management',
            '6' => 'Brand Shop',
            '7' => 'Tour Operator',
            '8' => 'Furniture',
            '9' => 'Hospital',
            '10' => 'Car Garage',
            '11' => 'Fashion House',
            '12' => 'Gym/Fitness',
            '13' => 'Pharmacy',
            '14' => 'Saloon',
            '15' => 'Ceramics',
            '16' => 'Sharee House',
        ];
        return $category;
    }
}

if (!function_exists('getBdEquivDateTime')) {
    function getBdEquivDateTime($mysql_format)
    {
        if (empty($mysql_format)) return "";
        $dt = new DateTime($mysql_format, new DateTimezone('UTC'));
        $dt->setTimezone(new DateTimezone('Asia/Dhaka'));
        return $dt->format('d M Y h:i A');
    }
}

if (!function_exists('get_vehicle_type_image_by_id')) {
    function get_vehicle_type_image_by_id($type_id)
    {
        $vehicle_type_image = '';
        switch($type_id){
            case 1:
                $vehicle_type_image = URL('backend/images/vehicle-type/car.png');
                break;

            case 2:
                $vehicle_type_image = URL('backend/images/vehicle-type/bike.png');
                break;

            case 3:
                $vehicle_type_image = URL('backend/images/vehicle-type/am1.png');
                break;

            case 4:
                $vehicle_type_image = URL('backend/images/vehicle-type/am2.png');
                break;

            case 5:
                $vehicle_type_image = URL('backend/images/vehicle-type/am3.png');
                break;

            case 6:
                $vehicle_type_image = URL('backend/images/vehicle-type/am4.png');
                break;

            case 7:
                $vehicle_type_image = URL('backend/images/vehicle-type/car.png');
                break;

            case 8:
                $vehicle_type_image = URL('backend/images/vehicle-type/micro1.png');
                break;

            case 9:
                $vehicle_type_image = URL('backend/images/vehicle-type/micro2.png');
                break;

            case 10:
                $vehicle_type_image = URL('backend/images/vehicle-type/car.png');
                break;

            case 11:
                $vehicle_type_image = URL('backend/images/vehicle-type/micro1.png');
                break;

            case 12:
                $vehicle_type_image = URL('backend/images/vehicle-type/micro2.png');
                break;

            case 13:
                $vehicle_type_image = URL('backend/images/vehicle-type/am2.png');
                break;
        }

        return $vehicle_type_image;
    }
}

if (!function_exists('getAgentDepthLevel')) {
    function getAgentDepthLevel($parent_id)
    {
        if ($parent_id == 0 || $parent_id == null || $parent_id == '') return 1;
        $depth = DB::table('agents')
            ->select(DB::raw('depth + 1 as depth'))
            ->where('id', $parent_id)
            ->first();
        if (!empty($depth)) {
            return $depth->depth;
        }
        return 1;
    }
}

if (!function_exists('getAllDateOfMonth')) {
    function getAllDateOfMonth($month = '', $year = '')
    {
        $month = $month == '' ? date('m') : $month;
        $year = $year == '' ? date('Y') : $year;
        $dates = [];
        for($d = 1; $d <= 31; $d++)
        {
            $time = mktime(12, 0, 0, $month, $d, $year);
            if (date('m', $time) == $month)
                $dates[] = date('d-m-Y', $time);
        }
        return $dates;
    }
}

if (!function_exists('dateRange')) {
    function dateRange($from, $to)
    {
        $begin = new DateTime($from);
        $end = new DateTime($to);
        $end = $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval, $end);
        return $daterange;
    }
}

if (!function_exists('getCompanySettings')) {
    function getCompanySettings($company_id)
    {
        $array = [];
        $rows = DB::table('settings_main')
            ->select('*')
            ->where('company_id', $company_id)
            ->get();

        if (!empty($rows)) {
            foreach ($rows as $val) {
                $array[$val->config_key] = $val->config_value;
            }
        } else {
            $array['max_depth'] = '';
            $array['tse_depth'] = '';
            $array['sr_depth'] = '';
        }

        return (object)$array;
    }
}

if (!function_exists('getCompanyIdByAgentId')) {
    function getCompanyIdByAgentId($agent_id)
    {
        $agent = DB::table('agents')
            ->select('company_id')
            ->where('id', $agent_id)
            ->first();
        if (!empty($agent)) {
            return $agent->company_id;
        }
        return 0;
    }
}

if (!function_exists('getAllDateOfRangePeriod')) {
    function getAllDateOfRangePeriod($from, $to)
    {
        $dates = [];
        $from = strtotime($from);
        $to = strtotime($to);
        for ($i = $from; $i <= $to; $i += 86400) {
            $dates[] = date("Y-m-d", $i);
        }

        return $dates;
    }
}

if (!function_exists('getTimeDifference')) {
    function getTimeDifference($time1, $time2) {
        if(($time1 == '' || $time1 == '00:00:00') || ($time2 == '' || $time2 == '00:00:00')) {
            return 0;
        }
        $time1 = strtotime("1980-01-01 $time1");
        $time2 = strtotime("1980-01-01 $time2");
        $min = abs($time2 - $time1)/60;
        if($min < 1){
            return 0;
        }
        return round($min/60,2);
        /*
        if ($time2 < $time1) {
            $time2 += 86400;
        }
        return date("H:i:s", strtotime("1980-01-01 00:00:00") + ($time2 - $time1));*/
    }
}

if (!function_exists('getTimeDifferenceInMinute')) {
    function getTimeDifferenceInMinute($time1, $time2) {
        if(($time1 == '' || $time1 == '00:00:00') || ($time2 == '' || $time2 == '00:00:00')) {
            return 0;
        }
        $time1 = strtotime("1980-01-01 $time1");
        $time2 = strtotime("1980-01-01 $time2");
        return floor(abs($time2 - $time1)/60);
    }
}

if (!function_exists('getMerchantId')) {
    function getMerchantId($token) {
        $token_data = DB::table('member_tokens as mt')
            ->select('m.id')
            ->join('merchants as m','m.member_id', '=', 'mt.member_id')
            ->where([ 'mt.member_type' => 2, 'mt.token' => $token, 'is_expire' => 0 ])
            ->first();
        if(!empty($token_data)){
            return $token_data->id;
        }
        return '';
    }
}
if (!function_exists('getMerchantIdByMemberId')) {
    function getMerchantIdByMemberId($memberId) {
        $merchant = DB::table('merchants as m')
            ->select('m.id')
            ->where([ 'm.member_id' => $memberId])
            ->first();
        if(!empty($merchant)){
            return $merchant->id;
        }
        return '';
    }
}

if (!function_exists('storeTrackingData')) {
    function storeTrackingData($trackArray) {
        //DB::beginTransaction();
        try {
            $check = TrackingDetails::select('id')
                ->where(['deliveries_id' => $trackArray['deliveries_id'], 'flag_status_id' => $trackArray['flag_status_id'] ])
                ->first();
            if (!empty($check))
            {
                $track = TrackingDetails::find($check->id);
            }else{
                $track = new TrackingDetails();
            }

            $track->deliveries_id = $trackArray['deliveries_id'];
            $track->flag_status_id = $trackArray['flag_status_id'];
            $track->assign_to = $trackArray['assign_to'];
            $track->is_hub = $trackArray['is_hub'];
            $track->notes = $trackArray['notes'];
            $track->in_out = $trackArray['in_out'];
            $track->is_active = 1;
            $track->entry_by = !empty(Auth::user()->id) ? Auth::user()->id :null;
            $track->description = $trackArray['description'];
            $track->created_at = date('Y-m-d H:i:s');
            if($track->save()){
                $summary = TrackingDetails::where('deliveries_id',$track->deliveries_id)->get()->toArray();
                $serialized_data = serialize($summary);
                $details = TrackingDetailsSummary::where('deliveries_id',$track->deliveries_id)->first();
                if (empty($details)){
                    $details = new TrackingDetailsSummary();
                }
                $details->deliveries_id = $track->deliveries_id;
                $details->hub_id = empty($trackArray['hub_id']) ? 0 : $trackArray['hub_id'];
                $details->in_out = $trackArray['in_out'];
                $details->details = $serialized_data;
                $details->created_at = date('Y-m-d H:i:s');
                $details->save();
                $deliveries = Delivery::find($track->deliveries_id);
                $deliveries->status = $trackArray['flag_status_id'];
                if ($trackArray['flag_status_id'] == 6 || $trackArray['flag_status_id'] == 12  || $trackArray['flag_status_id'] == 20)  {
                    $deliveries->invoice_date = date("Y-m-d");
                    $deliveries->receive_amount = $trackArray['receive_amount'];
                    $deliveries->cod_charge = get_merchant_cod_charge_by_merchant_id($deliveries->merchant_id, $trackArray['receive_amount']);
                    $deliveries->delivery_date = array_key_exists("rescheduled_date", $trackArray) && !empty($trackArray["rescheduled_date"]) ? $trackArray["rescheduled_date"] : date("Y-m-d");
                    $deliveries->return_date = null;
                }
                if ($trackArray['flag_status_id'] == 7 || $trackArray['flag_status_id'] == 8 || $trackArray['flag_status_id'] == 16) {
                    $deliveries->invoice_date = date("Y-m-d");
                    $deliveries->receive_amount = $trackArray['receive_amount'];
                    $deliveries->charge = getPlanChargeByPlanId($deliveries->plan_returned_id);
                    $deliveries->cod_charge = get_merchant_cod_charge_by_merchant_id($deliveries->merchant_id, $trackArray['receive_amount']);
                    $deliveries->return_date = date("Y-m-d");
                    $deliveries->delivery_date = null;
                }
                //if ($trackArray['flag_status_id'] == 10) {
//                    $contact_no = $deliveries->recipient_number;
//                    $message = "Hi {$deliveries->recipient_name},
//                    Item(s) from your order are on its way to you.
//                    Track your package on www.parcelbd.com by using your tracking number '{$deliveries->consignment_id}'
//                    Click here: http://admin.parcelbd.com/consignment-tracking/{$deliveries->consignment_id}
//                    Thanks
//                    ParcelBD";
//                    send_sms($contact_no, $message);

 //               }
//                if ($trackArray['flag_status_id'] == 9) {
//                    //$contact_no = $deliveries->recipient_number;
//                    $contact_no = "8801674304024";
//                    $message = "Hi {$deliveries->recipient_name},
//                    Your parcel is on hold Because({$trackArray['notes']}).
//                    For any complain please call this number 01958486571.
//                    Thanks
//                    ParcelBD";
//                    send_sms($contact_no, $message);
//                }
                $assignRiders = TrackingDetails::where(
                    [
                        'deliveries_id' => $track->deliveries_id,
                        'flag_status_id' => 10,
                        'is_hub' => 0,
                    ]
                )
                    ->whereNotIn('assign_to',[$trackArray['assign_to']])
                    ->get();
                if (!empty($assignRiders))
                {
                    foreach ($assignRiders as $activeRider)
                    {
                        $updateRider = TrackingDetails::find($activeRider->id);
                        $updateRider->is_active = 0;
                        $updateRider->update();
                    }
                }
                $deliveries->save();
                return $track->id;
            }

            //DB::commit();
            // all good
        } catch (Exception $e) {
            //DB::rollback();
            //return 0;
            return $e->getMessage();
        }

        return '';
    }
}

if (!function_exists('getPlanChargeByPlanId')) {
    /**
     * Helper to return the current login user id
     *
     * @return mixed
     */
    function getPlanChargeByPlanId($plan_id) {
        if ($plan_id) {
            $plan = Plans::find($plan_id);
            if (empty($plan))
            {
                return 0;
            }
            return $plan->charge;
        }
        return 0;
    }
}

if (!function_exists('get_merchant_id')) {
    /**
     * Helper to return the current login user id
     *
     * @return mixed
     */
    function get_merchant_id()
    {
        if (Auth::user()) {
            $user_session = Auth::user();
            $merchant = DB::table('merchants')->select('id')->where('member_id',$user_session->id)->first();
            return $merchant->id;
        }
    }
}

if (!function_exists('get_member_id_by_rider_id')) {
    /**
     * Helper to return the current login user id
     *
     * @return mixed
     */
    function get_member_id_by_rider_id($id)
    {
        $query = DB::table("riders")->select("member_id")->where("id", $id)->first();
        if (empty($query)) {
            return 0;
        }
        return $query->member_id;
    }
}

if (!function_exists('get_admin_hub_id')) {
    function get_admin_hub_id()
    {
        if (Auth::user()) {
            $user_session = Auth::user();
            $hub = DB::table('admin_users')->select('id','hub_id')->where('member_id',$user_session->id)->first();
            return $hub->hub_id;
        }
    }
}

if (!function_exists('send_mail')) {
    function send_mail($mail, $singleMerchant, $m_key, $invoices, $inv_notes)
    {
        $data = [
            'merchant' => $singleMerchant,
            'm_key' => $m_key,
            'invoices' => $invoices,
            'inv_notes' => $inv_notes,
        ];

        return Mail::send('admin.emails.invoice_template',$data, function($message) use ($mail)
        {
            $message->to($mail['to']);
            $message->subject($mail['subject']);
            $message->from($mail['from']);
        });
    }
}

if (!function_exists('get_merchant_cod_charge_by_consignment_id')) {
    function get_merchant_cod_charge_by_consignment_id($delivery_id)
    {
        $charge = DB::table('deliveries')->select('receive_amount','merchant_id')->where('id',$delivery_id)->first();
        if (!empty($charge)){
            $merchant = DB::table('merchants')->select('cod_percentage')->where('id',$charge->merchant_id)->first();
            if (!empty($charge)){
                $cod_charge = ($charge->receive_amount * $merchant->cod_percentage)/100;
                return $cod_charge;
            }
        }
        return 0;
    }
}

if (!function_exists('get_merchant_cod_charge_by_merchant_id')) {
    function get_merchant_cod_charge_by_merchant_id($merchant_id, $receive_amount)
    {
        $merchant = DB::table('merchants')->select('cod_percentage','cod_limit_amount')->where('id',$merchant_id)->first();
        if (!empty($merchant)){
            if ($receive_amount > $merchant->cod_limit_amount){
                $receive_amount = $receive_amount - $merchant->cod_limit_amount;
                $cod_charge = ($receive_amount * $merchant->cod_percentage)/100;
            }else{
                $cod_charge = ($receive_amount * $merchant->cod_percentage)/100;
            }
            return $cod_charge;
        }
        return 0;
    }
}

if (!function_exists('get_dashboard_notifications')) {
    function get_dashboard_notifications()
    {
        $currenTime = date("Y-m-d H:i:s");
        $aData =  DB::table('merchants as m')
            ->whereBetween('created_at', [date("Y-m-d H:i:s", strtotime("-6 hours")), $currenTime])
            ->where('m.status',0)
            ->count();
        return $aData;
    }
}
/* Usage for import CSV */
if (!function_exists('getMerchantIdByMerchantCode')) {
     function getMerchantIdByMerchantCode($code) {
        $query = DB::table("merchants")
            ->select("id")
            ->where(["merchant_code" => $code])
            ->first();
        if (empty($query))
        {
            return 0;
        }
        return $query->id;
    }
}

if (!function_exists('getPlanIdByPlanCode')) {
     function getPlanIdByPlanCode($code) {
        $query = DB::table("plans")
            ->select("id")
            ->where(["plan_code" => $code])
            ->first();
        if (empty($query))
        {
            return 0;
        }
        return $query->id;
    }
}

if (!function_exists('getHubIdByHubCode')) {
     function getHubIdByHubCode($code) {
        $query = DB::table("hub")
            ->select("id")
            ->where(["hub_code" => $code])
            ->first();
        if (empty($query))
        {
            return 0;
        }
        return $query->id;
    }
}

if (!function_exists('getCourierZoneIdByZoneCode')) {
     function getCourierZoneIdByZoneCode($code) {
        $query = DB::table("courier_zones")
            ->select("id")
            ->where(["zone_code" => $code])
            ->first();
        if (empty($query))
        {
            return 0;
        }
        return $query->id;
    }
}

if (!function_exists('callGoogleMapApis')) {
     function callGoogleMapApis($address) {
        $client = new Client(); //GuzzleHttp\Client
        $result =(string) $client->post("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=AIzaSyC4I-UdSRHCSIMrLQlKE68krPHAQVXpzy4")->getBody();
        $json =json_decode($result);
        if (empty($json->results))
        {
            return '';
        }
        $cor['lat'] = $json->results[0]->geometry->location->lat;
        $cor['lng'] = $json->results[0]->geometry->location->lng;
        return $cor;
    }
}

if (!function_exists('getCoordinateByRecipientAddress')) {
     function getCoordinateByRecipientAddress($address, $google_map_address) {
         if (!empty($google_map_address))
         {
             $address = $google_map_address;
         }else {
             $address = $address;
         }
        $result = callGoogleMapApis($address);
        if (empty($result)) {
            $result = callGoogleMapApis($address);
            if (empty($result)) {
                if (strpos($address, ",")) {
                    $result = callGoogleMapApis(end(explode(",",$address)));
                }
                if (empty($result)) {
                    $cor = ['lat' => '0.00000000', 'lng' => '0.00000000'];
                    return $cor;
                }
            }

        }
        return $result;
    }
}

if (!function_exists('postExportFile')) {
    function postExportFile($request, $data, $fileName, $forceDownload = false)
    {
        $export_type    = 'csv';
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = !empty($fileName) ? $fileName : 'Export-Invoice-';
        $file_name = $file_name. date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = !empty($fileName) ? $fileName : 'Export-Invoice-';
            $file_name = $file_name . $start_date . '-To-' . $end_date;
        }

        // $data = [ 'Nmae' => "Mamun"];
        if (empty($data)) {
            $this->response['success'] = false;
            $this->response['msg']  = "Didn't found any data !";
            return response($this->response,200);
        }

        return Excel::create($file_name, function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->store($export_type, 'exports/', true);
    }
}
/* END */