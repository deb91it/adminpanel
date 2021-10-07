<?php namespace App\Repositories\Api\Outlet;

use App\DB\Api\Store;
use App\DB\Api\OutletOrder;
use DB;

class EloquentStoreRepository implements StoreRepository
{
    protected $outlet;

    function __construct(Store $outlet)
    {
        $this->outlet = $outlet;
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getOutletCategory($company_id)
    {
        return DB::table('outlet_categories')
            ->selectRaw("id, name, IFNULL(category_image, '') as image, IFNULL(category_image_url, '') as image_url")
            ->where([ 'company_id' => $company_id, 'status' => 1])
            ->get();
    }

    public function addOutlet($input)
    {
        if($input['has_outlet'] == 0){
            $this->createOutletOwner($input);
            return true;
        }

        $outlet = new Store();
        list($lat, $lng) = explode(',', $input['coordinate']);
        $outlet->name           = $input['outlet_name'];
        $outlet->sr_id          = $input['sr_id'];
        $outlet->address        = $input['address'];
        $outlet->latitude       = $lat;
        $outlet->longitude      = $lng;
        $outlet->note           = isset($input['others']) ? $input['others']: '' ;
        $outlet->category_id    = isset($input['category_id']) ? $input['category_id'] : 1;
        $outlet->company_id     = getCompanyIdByAgentId($input['sr_id']);
        $outlet->owner_id       = $this->createOutletOwner($input);
        $outlet->established    = $input['established_year'];
        $outlet->ext_options    = $this->process_extra_options($input);
        $outlet->created_at     = date('Y-m-d h:i:s');
        if ($outlet->save()) {
            return $outlet->id;
        }
        return 0;
    }

    private function process_extra_options($input) {
        $option_array = [
            'posm'      => isset($input['posm']) ? $input['posm'] : 0,
            'muffin'    => isset($input['muffin']) ? $input['muffin'] : 0,
            'section'   => isset($input['section']) ? $input['section'] : ''
        ];
        return json_encode($option_array);
    }

    /*private function saveOutletOptions($input, $outlet_id){
        $basics = [
            'coordinate' => '',
            'outlet_name' => '',
            'sr_id' => '',
            'address' => '',
            'tse_id' => '',
            'others' => '',
            'category_id' => '',
            'established_year' => '',
            'outlet_id' => '',
            'owner_id' => '',
            'has_outlet' => '',
            'first_name' => '',
            'last_name' => '',
            'last_name' => '',
            'email' => '',
            'phone' => '',
            'gender' => '',
        ];

        DB::table('outlet_options')->where('outlet_id', $outlet_id)->delete();
        $options = array_diff_key($input, $basics);

        if(!empty($options)) {
            foreach($options as $key => $val) {
                DB::table('outlet_options')->insert([
                    [
                        'option_key'    => $key,
                        'option_value'  => $val,
                        'outlet_id'     => $outlet_id
                    ]
                ]);
            }
        }

        return true;
    }*/

    public function createOutletOwner($input, $owner_id = null)
    {
        if ($owner_id) {
            DB::table('outlet_owners')
                ->where('id', $owner_id)
                ->update([
                    'first_name'        => $input['first_name'],
                    'last_name'         => $input['last_name'],
                    'email'             => $input['email'],
                    'phone'             => $input['phone'],
                    'gender'            => $input['gender'],
                    'has_outlet'        => $input['has_outlet'],
                    'sr_id'             => $input['sr_id'],
                    'updated_at'        => date('Y-m-d h:i:s')
                ]);
        } else {
            $owner_id = DB::table('outlet_owners')->insertGetId(
                [
                    'first_name'        => $input['first_name'],
                    'last_name'         => $input['last_name'],
                    'email'             => $input['email'],
                    'phone'             => $input['phone'],
                    'gender'            => $input['gender'],
                    'has_outlet'        => $input['has_outlet'],
                    'sr_id'             => $input['sr_id'],
                    'created_at'        => date('Y-m-d h:i:s')
                ]);
        }
        return $owner_id;
    }

    public function updateOutlet($input, $id)
    {
        if($input['has_outlet'] == 0){
            $this->createOutletOwner($input, 'owner_id');
            return true;
        }

        list($lat, $lng) = explode(',', $input['coordinate']);
        $outlet = $this->outlet->find($id);
        if(empty($outlet)){
            return 0;
        }
        $outlet->name           = $input['outlet_name'];
        $outlet->sr_id          = $input['sr_id'];
        $outlet->address        = $input['address'];
        $outlet->latitude       = $lat;
        $outlet->longitude      = $lng;
        $outlet->note           = isset($input['others']) ? $input['others'] : '' ;
        $outlet->category_id    = isset($input['category_id']) ? $input['category_id'] : 1;
        $outlet->company_id     = getCompanyIdByAgentId($input['sr_id']);
        $outlet->owner_id       = $this->createOutletOwner($input, $outlet->owner_id);
        $outlet->established    = $input['established_year'];
        $outlet->ext_options    = $this->process_extra_options($input);
        $outlet->updated_at     = date('Y-m-d h:i:s');
        if ($outlet->save()) {
            return $outlet->id;
        }
        return 0;
    }

    public function addOutletOrder($inputs)
    {
        $oOrder = new OutletOrder();
        $oOrder->sr_id             = isset($inputs['sr_id']) ? $inputs['sr_id'] : 0;
        $oOrder->company_id        = $inputs['company_id'];
        $oOrder->outlet_id         = $inputs['outlet_id'];
        $oOrder->is_visited        = isset($inputs['is_visited']) ? $inputs['is_visited'] : 0;
        $oOrder->is_ordered        = isset($inputs['is_ordered']) ? $inputs['is_ordered'] : 0;
        $oOrder->ordered_amount    = isset($inputs['ordered_amount']) ? $inputs['ordered_amount'] : 0;
        $oOrder->ordered_weight    = isset($inputs['ordered_weight']) ? $inputs['ordered_weight'] : 0;
        $oOrder->has_meeting       = isset($inputs['has_meeting']) ? $inputs['has_meeting'] : 0;
        $oOrder->meeting_time      = isset($inputs['meeting_time']) ? $inputs['meeting_time'] : null;
        $oOrder->remarks           = isset($inputs['remarks']) ? $inputs['remarks'] : '';
        $oOrder->coordinate        = isset($inputs['coordinate']) ? $inputs['coordinate'] : '';
        $oOrder->status            = 1;
        $oOrder->created_at    = date('Y-m-d h:i:s');
        if ($oOrder->save()) {
            return $oOrder->id;
        }
        return 0;
    }

    public function isOutletOrderExist($inputs)
    {
        $from =  date("Y-m-d")." 00:00:00";
        $to =  date("Y-m-d")." 23:59:59";

        return DB::table('sr_outlet_order')->where(
            [   'sr_id'         => $inputs['sr_id'],
                'company_id'    => $inputs['company_id'],
                'outlet_id'     => $inputs['outlet_id'],
                'created_at'    => date('Y-m-d')
            ]
        )->whereRaw("created_at BETWEEN '{$from}' AND '{$to}'")->first();
    }

    public function postOrderUpdate($inputs,$id)
    {
        $oOrder                    = OutletOrder::find($id);
        $oOrder->sr_id             = isset($inputs['sr_id']) ? $inputs['sr_id'] : 0;
        $oOrder->company_id        = $inputs['company_id'];
        $oOrder->outlet_id         = $inputs['outlet_id'];
        $oOrder->is_visited        = isset($inputs['is_visited']) ? $inputs['is_visited'] : 0;
        $oOrder->is_ordered        = isset($inputs['is_ordered']) ? $inputs['is_ordered'] : 0;
        $oOrder->ordered_amount    = isset($inputs['ordered_amount']) ? $inputs['ordered_amount'] : 0;
        $oOrder->ordered_weight    = isset($inputs['ordered_weight']) ? $inputs['ordered_weight'] : 0;
        $oOrder->has_meeting       = isset($inputs['has_meeting']) ? $inputs['has_meeting'] : 0;
        $oOrder->meeting_time      = isset($inputs['meeting_time']) ? $inputs['meeting_time'] : null;
        $oOrder->remarks           = isset($inputs['remarks']) ? $inputs['remarks'] : '';
        $oOrder->coordinate        = isset($inputs['coordinate']) ? $inputs['coordinate'] : '';
        $oOrder->status       = 1;
        $oOrder->updated_at    = date('Y-m-d h:i:s');
        if ($oOrder->save()) {
            return $oOrder->id;
        }
        return 0;
    }

    public function outletLists($inputs){
        api_logs("sr/outletLists", "Salestrack Outlet", "O_21312" , json_encode($inputs), "what happen to you", 'ListOutlets Sign Updatdat', 12334);
        $filterType =  $inputs['filter_type'];
        $distance =  !empty($inputs['distance']) ? $inputs['distance']:1;
        $data =[];
        switch ($filterType){
            case "all":

                $data = DB::table('outlets as outlet')
                    ->select('outlet.id as outlet_id',
                        'outlet.outlet_make_id as sr_id',
                        'outlet.license_plate as outlet_name',
                        'outlet.make_year as established_year',
                        'outlet.model as full_name',
                        'outlet.license_key as address',
                        'outlet.license_city_name as email',
                        'outlet.license_number as lat',
                        'outlet.longitude as lng',
                        'outlet.color as phone',
                        'outlet.license_city_id as gender',
                        'outlet.created_at as datetime')
                    ->Leftjoin('sr_outlet_order as sr_outlet', 'outlet.id', '=', 'sr_outlet.outlet_id')
                    ->where('outlet_make_id',$inputs['sr_id'])
                    ->orderBy('outlet.id','DESC')
                    ->get();
                break;
            case "visited":
                $data = DB::table('outlets as outlet')
                    ->where('outlet_make_id',$inputs['sr_id'])
                    ->Leftjoin('sr_outlet_order as sr_outlet', 'outlet.id', '=', 'sr_outlet.outlet_id')
                    ->select('outlet.id as outlet_id',
                        'outlet.outlet_make_id as sr_id',
                        'outlet.license_plate as outlet_name',
                        'outlet.make_year as established_year',
                        'outlet.model as full_name',
                        'outlet.license_key as address',
                        'outlet.license_city_name as email',
                        'outlet.license_number as lat',
                        'outlet.longitude as lng',
                        'outlet.color as phone',
                        'outlet.license_city_id as gender',
                        'outlet.created_at as datetime')
                    ->where('sr_outlet.is_visited',1)
                    ->where('sr_outlet.created_at',date('Y-m-d'))
                    ->orderBy('outlet.id','DESC')
                    ->get();
                break;
            case "nearby":
                //echo $inputs["lat"];exit();
//                if (empty($inputs["lat"]) || $inputs["lng"]){
//                    return "";exit();
//                }
                $data = DB::select(DB::raw('SELECT outlet.id as outlet_id, 
                outlet.outlet_make_id as sr_id, 
                outlet.license_plate as outlet_name,
                outlet.make_year as established_year,
                outlet.model as full_name,
                outlet.license_key as address,
                outlet.license_city_name as email,
                outlet.license_number as lat,
                outlet.longitude as lng,
                outlet.color as phone,
                outlet.license_city_id as gender,
                outlet.created_at as datetime,
                (3956 * 2 * ASIN(SQRT( POWER(SIN(( '.$inputs["lat"].' - license_number) *  pi()/180 / 2), 2) +COS( '.$inputs["lat"].' * pi()/180) * COS(license_number * pi()/180) * POWER(SIN(( '.$inputs["lng"].' - longitude) * pi()/180 / 2), 2) ))) as distance  
                        from outlets as outlet left join  sr_outlet_order as sr_outlet on outlet.id = sr_outlet.outlet_id
                        WHERE outlet.outlet_make_id = '.$inputs['sr_id'].'
                        having  distance <= '.$distance.' 
                        order by distance'));
                break;
        }
        return $data;

    }

    public function getDriverOutlet_DAED($driver_id)
    {
        return DB::table('outlets as v')
            ->select(
                'v.id', 'v.model', 'v.make_year', 'v.license_plate', 'v.color',
                'vc.id as category_id', 'vc.type_name as category_name',
                'vm.id as outlet_make_id', 'vm.name as outlet_make_name',
                'vr.image_file as reg_image', 'vr.is_verified as reg_is_verified', DB::raw('CONCAT("'.url('resources/outlet/registration').'/", vr.image_file) AS reg_image_url') ,
                'vi.image_file as ins_image', 'vi.is_verified as ins_is_verified', DB::raw('CONCAT("'.url('resources/outlet/insurance').'/", vi.image_file) AS ins_image_url') ,
                'vfc.image_file as fitc_image', 'vfc.is_Giveverified as fitc_is_verified', DB::raw('CONCAT("'.url('resources/outlet/fitness').'/", vfc.image_file) AS fitc_image_url') ,
                'voc.image_file as objc_image', 'voc.is_verified as objc_is_verified', DB::raw('CONCAT("'.url('resources/outlet/objection').'/", voc.image_file) AS objc_image_url') ,
                'vtt.image_file as taxt_image', 'vtt.is_verified as taxt_is_verified', DB::raw('CONCAT("'.url('resources/outlet/tax_token').'/", vtt.image_file) AS taxt_image_url'),
                'dav.is_active'
            )
            ->join('drivers_outlets as dv', 'dv.outlet_id', '=', 'v.id')
            ->join('outlet_categories as vc', 'vc.id', '=', 'v.category_id')
            ->join('outlet_makes as vm', 'vm.id', '=', 'v.outlet_make_id')
            ->leftJoin('outlet_registrations as vr', 'vr.outlet_id', '=', 'v.id')
            ->leftJoin('outlet_insurances as vi', 'vi.outlet_id', '=', 'v.id')
            ->leftJoin('outlet_fitness_certificates as vfc', 'vfc.outlet_id', '=', 'v.id')
            ->leftJoin('outlet_objection_certificates as voc', 'voc.outlet_id', '=', 'v.id')
            ->leftJoin(DB::raw('(SELECT outlet_id, is_active FROM driver_active_outlet WHERE is_active = 1) dav'), function($join)
            {
                $join->on('dav.outlet_id', '=', 'v.id');
            })
            ->leftJoin('outlet_tax_tokens as vtt', 'vtt.outlet_id', '=', 'v.id')
            ->where('dv.driver_id', $driver_id)
            ->where('v.status', 1)
            ->get();
    }

    public function getDriverOutlet($driver_id)
    {
        return DB::table('outlets as v')
            ->select(
                'v.id', 'v.model', 'v.make_year', 'v.license_plate', 'v.color',
                'vc.id as category_id', 'vc.type_name as category_name',
                'vm.id as outlet_make_id', 'vm.name as outlet_make_name',            
                'dav.is_active'
            )
            ->join('drivers_outlets as dv', 'dv.outlet_id', '=', 'v.id')
            ->join('outlet_categories as vc', 'vc.id', '=', 'v.category_id')
            ->join('outlet_makes as vm', 'vm.id', '=', 'v.outlet_make_id')            
            ->leftJoin(DB::raw('(SELECT outlet_id, is_active FROM driver_active_outlet WHERE is_active = 1) dav'), function($join)
            {
                $join->on('dav.outlet_id', '=', 'v.id');
            })            
            ->where('dv.driver_id', $driver_id)
            ->where('v.status', 1)
            ->get();
    }

    public function getMarchantOutlet($marchant_id)
    {
        // TODO: Implement getMarchantOutlet() method.
    }

    public function getOutlet($request, $sr_id, $tse_id)
    {
        $from =  date("Y-m-d")." 00:00:00";
        $to =  date("Y-m-d")." 23:59:59";
        $sr_ids = [];
        if ($sr_id > 0) {
            $sr_ids[] = $sr_id;
        } elseif ($tse_id > 0) {
            $sr_ids = $this->getAllSrIdOfTse($tse_id);
        } else {
            return [];
        }

        $rows = DB::table('outlets as o')
            ->select(
                'o.*',
                'oc.id as category_id', 'oc.name as category_name', 'category_image', 'category_image_url',
                DB::raw("(SELECT GROUP_CONCAT(
                    CONCAT(
                        'first_name:', first_name, 
                        ',', 
                        'last_name:', last_name, 
                        ',', 
                        'email:', email,
                        ',', 
                        'phone:', phone,
                        ',', 
                        'gender:', gender
                     )
                    SEPARATOR ', ') 
                 FROM outlet_owners WHERE id = o.owner_id) as owner")
            )
            ->join('outlet_categories as oc', 'oc.id', '=', 'o.category_id');

            if(isset($request['key']) && $request['key'] == 'visited' && $sr_id > 0) {
                $rows = $rows->join(
                    DB::raw("
                        (
                            SELECT 
                                outlet_id 
                            FROM sr_outlet_order 
                             WHERE sr_id ={$sr_id} AND (created_at BETWEEN '{$from}' AND '{$to}')
                            AND is_visited = 1
                            GROUP BY outlet_id
                        ) as so"), 'so.outlet_id', '=', 'o.id');
            } else if(isset($request['key']) && $request['key'] == 'nearby' && $sr_id > 0) {

                if(!isset($request['cords']) || $request['cords'] == ''){
                    return [];
                }

                list($lat, $lng) = explode(',', $request['cords']);
                $rows = $rows->join(
                    DB::raw("
                        (
                            SELECT 
                                id,
                                ( ACOS( COS( RADIANS( {$lat}  ) ) 
                                      * COS( RADIANS( latitude ) )
                                      * COS( RADIANS( longitude ) - RADIANS( {$lng} ) )
                                      + SIN( RADIANS( {$lat}  ) )
                                      * SIN( RADIANS( latitude ) )
                                  )
                                * 6371
                                ) AS distance_in_km
                            FROM outlets HAVING distance_in_km <= 2
                        ) as cord"), 'cord.id', '=', 'o.id');
            }
        $rows = $rows->whereIn('o.sr_id', $sr_ids)
            ->where([ 'o.status' => 1 ])
            ->orderBy('o.id', 'desc')
            ->get();

        if (empty($rows)) {
            return [];
        }

        foreach ($rows as $row) {
            $owner = [];
            foreach (explode(',', $row->owner) as $v) {
                $item = explode(':', $v);
                $owner[$item[0]] = $item[1];
            }
            $row->owner = $owner;

            foreach (json_decode($row->ext_options) as $k => $val) {
                $row->{$k} = $val;
            }
        }


        return $rows;
    }

    private function getAllSrIdOfTse($tse_id){
        $sr_ids = [];
        $sr_rows = DB::select("SELECT id FROM agents WHERE parent_id = {$tse_id}");
        if(!empty($sr_rows)){
            foreach ($sr_rows as $val) {
                $sr_ids[] = $val->id;
            }
        }
        return $sr_ids;
    }

    public function isOutletExist($inputs)
    {
        $coordinate = explode(",",$inputs['coordinate']);
        return $outlet = $this->outlet->where(
            ['outlet_make_id'   => $inputs['sr_id'],
                'outlet_make_id'   => $inputs['sr_id'],
                'license_city_id'   => $inputs['gender'],
                'license_key'       => $inputs['address'],
                'license_number'    => $coordinate[0],
                'longitude'    => $coordinate[1]
            ]
        )->first();
    }

    public function destroy($id, $driver_id)
    {
        $outlet = $this->outlet->where(['driver_id' => $driver_id, 'id' => $id])->first();
        if (empty($outlet)) {
            return false;
        }
        $outlet->status = 0;
        if ($outlet->save()) {
            return true;
        }
        return false;
    }

    public function assignDriverToOutlet($outlet_id, $driver_id, $merchant_id)
    {
        $outletAssignId = DB::table('drivers_outlets')
            ->select('id')
            ->where('driver_id', $driver_id)
            ->where('outlet_id', $outlet_id)
            ->first();

        if(empty($outletAssignId))
        {
            return DB::table("drivers_outlets")
                ->insertGetId([
                    'merchant_id'   => $merchant_id,
                    'outlet_id'    => $outlet_id,
                    'driver_id'     => $driver_id,
                    'status'        => 0
                ]);    
        }

        return $outletAssignId->id;
    }    

    public function getOutletRegAllData()
    {
        $data['brands']             = $this->getOutletMake();
        $data['outlet_reg_cities']  = $this->getAllDistricts();
        $data['zones']              = $this->getAllCities();        
        
        return $data;
    }
    
    public function getOutletMake()
    {   
        $brandData = DB::table('outlet_makes as vmake')
                        ->select('vmake.id', 'vmake.name','vmake.outlet_types')
                        ->where('vmake.status', 1)
                        ->orderBy('vmake.id', 'ASC')
                        ->get();
        
        $data = [];
        
        foreach ($brandData as $row) {
            $modelData = DB::table('outlet_models')
                            ->select('id', 'model_name', 'first_make_year')
                            ->where('make_id', $row->id)
                            ->orderBy('id', 'ASC')
                            ->get();
            
            $row->models = $modelData;
            
            $data[] = $row;
        }
        return $data;        
    }
    
    public function getOutletMakeAll($member_id = 0)
    {
        $vData = DB::table('members as mb')
                        ->select('d.outlet_category_id')
                        ->join('drivers as d', 'd.member_id', '=', 'mb.id')
                        ->where('mb.id', $member_id)
                        ->first();
        $outlet_category_id = $vData->outlet_category_id;
        
        if(!empty($vData))
        {
            $brandData = DB::table('outlet_makes as vmake')
                        ->select('vmake.id', 'vmake.name')
                        ->where('vmake.status', 1)
                        ->where('vmake.outlet_types', $outlet_category_id)
                        ->orderBy('vmake.id', 'ASC')
                        ->get();
        }
        else
        {
            $brandData = DB::table('outlet_makes as vmake')
                        ->select('vmake.id', 'vmake.name')
                        ->where('vmake.status', 1)
                        ->orderBy('vmake.id', 'ASC')
                        ->get();
        }
        
        
        $data = [];
        
        foreach ($brandData as $row) {
            $modelData = DB::table('outlet_models')
                            ->select('id', 'model_name', 'first_make_year')
                            ->where('make_id', $row->id)
                            ->orderBy('id', 'ASC')
                            ->get();
            
            $row->models = $modelData;
            
            $data[] = $row;
        }
        return $data;        
    }
    
    public function getAllDistricts()
    {
        $citisData = DB::table('outlet_registration_citys')
            ->select('id', 'name', 'code')            
            ->orderBy('id', 'ASC')
            ->get();
        
        return $citisData;        
    }
    
    public function getAllCities()
    {
        $citisData = DB::table('zones')
            ->select('zone_id', 'name', 'code')            
            ->orderBy('zone_id', 'ASC')
            ->get();
        
        return $citisData;        
    }

    public function saveOutletImage($inputs)
    {
        $save_path = public_path("resources/outlet/");
        $file = $inputs->file('_image');
        $image_name = "outlet_".$inputs['outlet_id']."_".time()."_".$file->getClientOriginalName();
        $file->move($save_path, $image_name);
        \Image::make(sprintf($save_path.'%s', $image_name))->save();
        $image_mime = \Image::make($save_path . $image_name)->mime();

        $data = DB::table('outlets')
            ->select('id', 'image_url')
            ->where('id', $inputs['outlet_id'])
            ->first();

        if(empty($data)){
            return false;
        }

        $imageUrl = url("resources/outlet")."/".$image_name;
        $exist_img = public_path("resources/outlet")."/".$data->image_url;
        if ($data->image_url != '') {
            if (file_exists($exist_img)) {
                unlink($exist_img);
            }
        }

        DB::table('outlets')
            ->where(['id' => $data->id])
            ->update([
                'image'         => $image_name,
                'image_url'     => $imageUrl,
                'updated_at'    => date('Y-m-d H:i:s')
            ]);

        return $imageUrl;
    }
}
