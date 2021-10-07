<?php namespace App\Repositories\Api\UtilsApi;
use DateTime;
use DB;
use Storage;

class EloquentUtilsApiRepository implements UtilsApiRepository
{
    //protected $utilsApi;

    function __construct()
    {
       // $this->$utilsApi = $utilsApi;
       date_default_timezone_set('Asia/Dhaka');
    }

    public function getActivePaymentMethods()
    {
        return DB::table('payment_methods')
            ->select('id', 'name', 'code')
            ->where(['status' => 1])
            ->get();
    }

    public function getSiteSettings($driver_id, $vehicle_id)
    {
        $response = [];
        $price = DB::table('vehicle_categories as vc')
            ->select(
                'vc.base_price', 'vc.price_per_unit_distance', 'vc.price_per_unit_time'
            )
            ->join('vehicles as v','v.category_id', '=', 'vc.id')
            ->where(['v.id' => $vehicle_id, 'v.driver_id' => $driver_id, 'v.status' => 1, 'vc.status' => 1])
            ->first();

        $response['price']['base_price'] = isset($price->base_price) ? $price->base_price : '';
        $response['price']['price_per_unit_distance'] = isset($price->price_per_unit_distance) ? $price->price_per_unit_distance : '';
        $response['price']['price_per_unit_time'] = isset($price->price_per_unit_time) ? $price->price_per_unit_time : '';

        $method = DB::table('payment_methods')
            ->select('id', 'name', 'code')
            ->where(['status' => 1])
            ->get();

        $response['payment_method'] = $method;

        return $response;
    }



    public function getAppSettings($keys)
    {
        $cTitle         = "bangladesh_deafult";
        $zTitle         = "dhaka_deafult";
        $countrieData   = "";
        $zoneData       = "";
        $filename       = "";

        $arKeys         = explode(",", $keys);
        $count          = sizeof($arKeys);
        $lastItem       = array_values(array_slice($arKeys, -1))[0];
        $arLastItem     = explode("|", $lastItem);
//        return $arLastItem;
//        $service_data   = array('salestrack' => 1, 'marketplace' => 1, 'emergency' => 1, 'quest' => 0);
        $service_data   = array('provati-courier' => 1);



        if (in_array("android_sales_app_version_string", $arKeys))
        {
            $filename = "settings_main_objects/drivers/bangladesh_dhaka_settings_main.json";
        }

        if (in_array("android_passenger_app", $arKeys))
        {
            $filename = "settings_main_objects/passenger/bangladesh_dhaka_settings_main.json";
        }

        if (in_array("android_partner_app", $arKeys))
        {
            $filename = "settings_main_objects/partners/bangladesh_dhaka_settings_main.json";
        }

        $exists = Storage::disk('public')->exists($filename);

        if($exists)
        {
            //echo 1;
            $contents = Storage::disk('public')->get($filename);
            //print_r($contents);exit();

            $response = object_to_array(json_decode($contents));

            return $response;

        }


        if (in_array("android_sales_app_version_string", $arKeys))
        {
            $filename = "settings_main_objects/drivers/".strtolower($arLastItem[0])."_".strtolower($arLastItem[1])."_settings_main.json";
        }

        if (in_array("android_passenger_app", $arKeys))
        {
            $filename = "settings_main_objects/passenger/".strtolower($arLastItem[0])."_".strtolower($arLastItem[1])."_settings_main.json";
        }

        if (in_array("android_partner_app", $arKeys))
        {
            $filename = "settings_main_objects/partners/".strtolower($arLastItem[0])."_".strtolower($arLastItem[1])."_settings_main.json";
        }

        $exists   = Storage::disk('public')->exists($filename);

        if($exists)
        {
            //echo 1;
            $contents = Storage::disk('public')->get($filename);
            $response = $this->object_to_array(json_decode($contents));

            return $response;

        }
        $response = []; $i = 1;

        foreach ($arKeys as $value) {
            $value = trim($value);
            if($i == $count)
            {
                if($arLastItem[0] != "")
                {
                    $countrieData = DB::table('countries')->select("country_id")
                        ->where('name', 'LIKE', '%'.$arLastItem[0].'%')
                        //->where(['status' => 1])
                        ->first();
                }

                if($arLastItem[1] != "")
                {
                    $zoneData = DB::table('zones')->select("zone_id", 'country_id', "service_data")
                        ->where('name', 'LIKE', '%'.$arLastItem[1].'%')
                        ->where(['status' => 1])
                        ->first();
                }

                if(!empty($zoneData))
                {
                    $zoneData->currency = "BDT";
                    $zoneData->timezone = "Asia/Dhaka";

                    $zoneData->service_data = @unserialize($zoneData->service_data);

                    $response['zone_data']['zone_id']     = $zoneData->zone_id;
                    $response['zone_data']['country_id']  = $zoneData->country_id;

                    $response['zone_data']['currency']    = "BDT";
                    $response['zone_data']['timezone']    = "Asia/Dhaka";
                    $response['service_data']             = $service_data;


                    $cTitle         = $zoneData->country_id;
                    $zTitle         = $zoneData->zone_id;
                }
                else
                {
                    if(!empty($countrieData))
                    {
                        if( ( $countrieData->country_id ==  146 || $arLastItem[0] == "Myanmar" || $arLastItem[0] == "myanmar") )
                        {
                            $response['zone_data']['zone_id']     = 333;
                            $response['zone_data']['country_id']  = 146;
                            $response['zone_data']['currency']    = "MMK";
                            $response['zone_data']['timezone']    = "Asia/Rangoon";
                            $response['service_data']             = $service_data;

                            $cTitle         = "myanmar";
                            $zTitle         = "rangoon";
                        }
                        else
                        {
                            $response['zone_data']['zone_id']     = null;
                            $response['zone_data']['country_id']  = $countrieData->country_id;
                            $response['zone_data']['currency']    = "BDT";
                            $response['zone_data']['timezone']    = "Asia/Dhaka";
                            $response['service_data']             = $service_data;

                            $cTitle         = "bangladesh";
                            $zTitle         = "dhaka";
                        }
                    }
                    else
                    {
                        $response['zone_data']['zone_id']     = null;
                        $response['zone_data']['country_id']  = $countrieData->country_id;
                        $response['zone_data']['currency']    = "BDT";
                        $response['zone_data']['timezone']    = "Asia/Dhaka";
                        $response['service_data']             = $service_data;

                        $cTitle         = "bangladesh_no_cTitle";
                        $zTitle         = "dhaka_no_zTitle";
                    }

                }


                break;
            }

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
                if($value == "signup_vehicle_types")
                {
                    $response['signup_vehicle_types'] = DB::table('vehicle_categories')
                        ->select(
                            "id as vehicle_type_id",
                            "signup_title as vehicle_type_title",
                            "signup_icon_url"
                        )
                        ->where(['allow_signup' => 1])
                        ->where('signup_title', "!=", "")
                        ->get();
                }
                elseif ($value == "signup_vehicle_types") {

                }
                else
                {
                    $response[$value] = null;
                }
            }
            $i++;
        }





//        echo "===== ";
//        print_r($filename);
//        echo "<br> =====";
//        exit;

        Storage::disk('public')->put($filename, json_encode($response));

        return $response;
    }
//    public function getAppSettings($keys)
//    {
//        $arKeys = explode(",", $keys);
//
//        $response = [];
//        foreach ($arKeys as $value) {
//            $value = trim($value);
//            $method = DB::table('settings_main')
//                ->select('config_value')
//                ->where(['config_key' => trim($value)])
//                ->first();
//            if(!empty($method))
//            {
//                $unserialize = @unserialize($method->config_value);
//                if($unserialize === false) {
//                     $response[$value] = $method->config_value;
//                }
//                else {
//                    $response[$value] = @unserialize($method->config_value);
//                }
//
//            }
//            else
//            {
//                $response[$value] = 'UNDEFINED';
//            }
//
//        }
//        return $response;
//    }
    public function postDriverOnOff($driver_id, $value)
    {
        DB::table('drivers')
            ->where(['id' => $driver_id])
            ->update(['is_offline' => $value]);

        if ($value == 0) {
            $insert_id = DB::table('driver_on_off_history')
                ->insertGetId([
                    'driver_id' => $driver_id,
                    'online_at' => date('Y-m-d H:i:s'),
                ]);
        } else if ($value == 1) {
            $current_status = DB::table('driver_on_off_history')
                ->select('id','online_at','duration')
                ->orderBy('id', 'desc')
                ->first();
            if (! empty($current_status)) {
                if ($current_status->duration == 0) {
                    $from_time = strtotime($current_status->online_at);
                    $now = date('Y-m-d H:i:s');
                    $to_time = strtotime($now);
                    $duration_in_sec = abs($to_time - $from_time);
                    DB::table('driver_on_off_history')
                        ->where('id',$current_status->id)
                        ->update([
                            'offline_at' => $now,
                            'duration' => $duration_in_sec
                        ]);
                }
            }
        }
        return true;
    }
    
    public function storeAndReturnWebContentList($arData)
    {           
        $insert_id = 0;
        if(!empty($arData))
        {
            $insert_id = DB::table('blog_contents')
                ->insertGetId([
                    'headline'      => $arData['headline'],
                    'sub_head'      => $arData['sub_head'],
                    'image_url'     => $arData['image_url'],
                    'content'       => $arData['content'],
                    'publish_date'  => $arData['publish_date'],
                    'is_published'  => $arData['is_published'],
                    'content_type'  => $arData['content_type']
                ]);
        }        
        
        return (array) DB::table('blog_contents')
            ->select(
                "id",
                "category",
                "headline",
                "sub_head as subhead",
                "image_url",
                "content",
                "action",
                "action_value"
            )
            ->where(['is_published' => 1])
            ->orderBy('content_type', 'ASC')
            ->get();
        
        
        //return $insert_id;
        
    }
    
    
    
/*    public function getVehicleSettings($driver_id, $vehicle_id)
    {
        return DB::table('vehicle_categories as vc')
            ->select(
                'vc.base_price_distance as base_distance', 'vc.base_price', 'vc.price_per_unit_distance as unit_price'
            )
            ->join('vehicles as v','v.category_id', '=', 'vc.id')
            ->where(['v.id' => $vehicle_id, 'v.driver_id' => $driver_id, 'v.status' => 1, 'vc.status' => 1])
            ->first();
    }*/

    public function getDefaultSettings()
    {
        return (array) DB::table('vehicle_categories')
            ->select(
                "id",
                "type_name",
                "commission",
                "min_fare",
                "pk_base_fare",
                "pk_unit_fare",
                "pk_waiting_min",
                "pk_wtng_min_charge",            
                "opk_base_fare",
                "opk_unit_fare",
                "opk_waiting_min",
                "opk_wtng_min_charge"
            )
            ->where(['status' => 1])
            ->get();
    }

    public function getDefaultSettingsDev()
    {
        

        $groupData = DB::table('vehicle_groups')
            ->select(
                "id",
                "name",
                "allow_schedule"
            )
            ->where(['status' => 1])
            ->get();

        $data = [];
        
        foreach ($groupData as $row) {
            $vehicleTypesData = DB::table('vehicle_categories')
                            ->select(
                                "id",
                                "type_name",
                                "type_image_url",
                                "select_image_url",
                                "capacity",
                                "pk_base_fare",
                                "pk_unit_fare",
                                "pk_waiting_min",
                                "pk_wtng_min_charge",            
                                "opk_base_fare",
                                "opk_unit_fare",
                                "opk_waiting_min",
                                "opk_wtng_min_charge"
                            )
                            ->where(['status' => 1])
                            ->where(['group_id' => $row->id])
                            ->get();
            
            $row->vehicle_types = $vehicleTypesData;
            
            $data[] = $row;
        }

        return  $data;   
    }  
    
    public function getDriverPromotion()
    {
        $response = [];
        $categorys = DB::table('vehicle_categories')
            ->select('*')
            ->get();

        if ( !empty($categorys)) {

            foreach ($categorys as $rating) {
                $single = [];
                $single['vehicle_type']                     = $rating->type_name;
                $single['pick']['time']                     = "08:00 am - 08:00 pm";
                $single['pick']['base_fare']                = $rating->pk_base_fare;
                $single['pick']['unit_fare']                = $rating->pk_unit_fare;
                $single['pick']['waiting_min_charge']       = $rating->pk_wtng_min_charge;
                $single['off_pick']['time']                 = "08:00 pm - 08:00 am";
                $single['off_pick']['base_fare']            = $rating->opk_base_fare;
                $single['off_pick']['unit_fare']            = $rating->opk_unit_fare;
                $single['off_pick']['waiting_min_charge']   = $rating->opk_wtng_min_charge;

                $response['data'][] = $single;
            }
        }
        return $response;
    }
    
    public function passengerPromoDiscount($total_fare = 0, $vehicle_type = 0, $passenger_id = 0, $is_checkout_mood = false){
        $return_array['promo_code'] = '';
        $return_array['promo_amount'] = 0;        
        $passenger_id = $passenger_id;
        $a = 0;
        $discount = 0;
        $promoCode = "";
        $arVehicleType = array($vehicle_type, 0);
                
        $dataPromoCodes = DB::table('promo_codes as pc')
            ->select('pc.id as promo_code_id', DB::raw("CONCAT(pc.key,'',pc.code) as code"), 'pc.vehicle_category', 'pcr.id as rule_id', 'pcr.key', 'pcr.operator', 'pcr.value', 'pcp.total_used')
            ->join('promo_code_rules as pcr','pcr.promo_code_id', '=', 'pc.id')
            ->join('promo_code_passenger as pcp','pcp.promo_code_id', '=', 'pcr.promo_code_id')
            ->where('pcp.passenger_id', $passenger_id)            
            ->whereIn('pc.vehicle_category', $arVehicleType)
            ->where(['pcp.is_expire' => 0, 'pcp.is_applied' => 1, 'pc.is_active' => 1])
        ->orderBy('pc.priority', 'DESC')
            ->get();
        
      //prixt($dataPromoCodes,1);
        
        $rules = [];
        
        foreach ($dataPromoCodes as $key => $value){
            $rules[$value->promo_code_id][$value->key]      = $value->value;
            $rules[$value->promo_code_id]['total_used']     = $value->total_used;
            $rules[$value->promo_code_id]['code']           = $value->code;
        }

        date_default_timezone_set('Asia/Dhaka');
        //$startTime = $value['min_date']." ".$value['start_time'];
        //$endTime = $value['max_date']." ".$value['end_time'];
     //   echo $d1 = new DateTime($value['min_date']." ".$value['start_time']);
        //$d2 = new DateTime($value['max_date']." ".$value['end_time']);
        //$today = date("Y-m-d");
        //$time = date('H', strtotime(date("Y-m-d H:i:s")));
        //$today = time();
//echo          $now = new DateTime();
         $today =  strtotime("now");
//$startTime = strtotime("2017-12-18 23:59:59");
//echo strtotime("2017-12-18 23:59:59");
//$s = '06/10/2011 19:00:02';
//echo $date = strtotime($s);
//echo date('d/M/Y H:i:s', $date);exit;
//exit;
        foreach ($rules as $code_id => $value){

            $promo_discount_amt = 0;
            $startTime = strtotime( $value['min_date']." ".$value['start_time'] );
        $endTime = strtotime($value['max_date']." ".$value['end_time']);
            //if date is not valid
            if (($today < $startTime) || ($today > $endTime)) {
           
                if ($is_checkout_mood) {
                    //$this->makePromeCodeStatusExpire($code_id, $passenger_id);
                }
                 $a=1;
                continue;
                //if time is not valid
            }
            elseif ($value['total_used'] >= $value['max_trips']) {
                if ($is_checkout_mood) {
                    //$this->makePromeCodeStatusExpire($code_id, $passenger_id);
                }
                 $a=3;
                continue;
            } else {

                if ($value['flat_discount'] > 0) {
                    $discount += $value['flat_discount'];
                    $promo_discount_amt = $value['flat_discount'];
                    $promoCode = $value['code'];
                    break;
                } elseif ($value['percent_discount'] > 0) {
                    $percent_discount = ($value['percent_discount'] / 100) * $total_fare;
                    
                    if($vehicle_type == 1)
                    {
            if($percent_discount > 60 && $value['amount_discount'] > 0)
            {
                        $value['amount_discount'] = 60;
                        $discount += $value['amount_discount'];
                        $promo_discount_amt = $value['amount_discount'];
                        $promoCode = $value['code'];
                        break;
            } else {
                        $discount+= $percent_discount;
                        $promo_discount_amt = $percent_discount;
                        $promoCode = $value['code'];
                        break;
                        }
            
                    }
                    elseif ($percent_discount > $value['amount_discount'] && $value['amount_discount'] > 0) {
                        $discount += $value['amount_discount'];
                        $promo_discount_amt = $value['amount_discount'];
                        $promoCode = $value['code'];
                        break;
                    } else {
                        $discount+= $percent_discount;
                        $promo_discount_amt = $percent_discount;
                        $promoCode = $value['code'];
                        break;
                    }
                }                
            }
        }       
                
        //if discount amount is equal or higher than total fare
        if ($discount >= $total_fare) {
            $discount = $total_fare;
        }        
        
        $return_array['promo_code'] = $promoCode;
        $return_array['promo_amount'] = floor($discount);
    //prixt($return_array,1);
        return $return_array;
    }

    public function getEmergencyVehicleInCity($inputs)
    {
        $response = [];
        $categorys = DB::table('vehicle_categories')
            ->select('*')
            ->where('group_id', $inputs['vahicle_group_id'])
            ->get();
        
        if ( !empty($categorys)) {

            $i = 0;
            foreach ($categorys as $rating) {
                $single = [];
                $single['vehicle_type_id']                  = $rating->id;
                $single['vehicle_type']                     = $rating->type_name;
                $single['image_url']                        = "";
                $single['select_image_url']                 = "";
                $single['capacity']                         = $rating->capacity;
                $single['provider_name']                    = "Bangladesh Cardiac Hospital & Research Institute"; 
                $single['description']                      = "Now that you have your web server installed, you have many options for the type of content to serve and the technologies you want to use to create a richer experience.";
                $single['fare']                             = number_format((1000+($i*200)), 2, '.', '');
                $single['fare_hints']                       = "Fare is fixed in the city for any distance within our boundary";
                $single['facility']                         = array('Doctor', 'Brother', 'Sister', 'Oxygen', 'First Aid', 'Wheel Chair', 'Madicin');
                $single['time']                             = date("Y-m-d", strtotime('2017-12-18'));  
                $response['data'][] = $single;

                $i++;
            }
        }
        
        return $response;
    }
    
    protected function getDriverActiveVehicle($driver_id)
    {
        $result =  DB::table('vehicles as v')
            ->select(
                'v.id', 'v.model', 'v.make_year', 'v.license_plate', 'v.color',
                'vc.id as category_id', 'vc.type_name as category_type',
                'dav.activated_at'
            )
            ->join('drivers_vehicles as dv', 'dv.vehicle_id', '=', 'v.id')
            ->join('vehicle_categories as vc', 'vc.id', '=', 'v.category_id')
            ->join(DB::raw('(SELECT vehicle_id, activated_at,  is_active FROM driver_active_vehicle WHERE is_active = 1) dav'), function($join)
            {
                $join->on('dav.vehicle_id', '=', 'v.id');
            })
            ->where('dv.driver_id', $driver_id)
            ->where('v.status', 1)
            ->first();
            
        return (array)$result;
    }
}
