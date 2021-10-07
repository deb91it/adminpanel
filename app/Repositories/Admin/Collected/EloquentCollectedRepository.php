<?php namespace App\Repositories\Admin\Collected;
use App\DB\Admin\Delivery;
use App\DB\Admin\CollectedProducts;
use App\DB\Admin\Member;
use App\DB\Admin\TrackingDetails;
use App\DB\Permission;
use DB;
use Datatables;


class EloquentCollectedRepository implements CollectedRepository
{
    protected $Collected;

    function __construct(Delivery $Collected)
    {
        $this->Collected = $Collected;
    }

    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getAll() method.
    }

    public function getById($id, $status = 1)
    {
        // TODO: Implement getById() method.
    }

    public function create($inputs)
    {
        // TODO: Implement create() method.
    }

    private function getCharge($plan_id) {
        if ($plan_id) {
            return 20;
        }
        return 0;
    }
    private function CollectedProduct ($products, $Collected_id, $id) {
        if($id > 0) {
            // Delete existing
            DB::table('Collected_product')->where('Collected_id', $Collected_id)->delete();
        }
        if (!empty($products)) {
            foreach ($products as $k => $cat_id) {
                DB::table('Collected_product')->insert(
                    [
                        'Collected_id'    => $Collected_id,
                        'product_id'     => $cat_id,
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s')
                    ]
                );
            }
        }
        return true;
    }

    public function findOrThrowException($id)
    {
        return  DB::table('deliveries as del')
            ->select('del.*', 'dp.product_id')
            ->where('del.id', $id)
            ->leftjoin('Collected_product as dp', 'dp.Collected_id', '=', 'del.id' )
            ->first();
    }



    public function delete($id)
    {
        DB::table('Collected')
            ->where('id', $id)
            ->update([
                'status' => 0,
            ]);
        return true;
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }

    public function getUserDetails($member_id)
    {
        return $this->Collected->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $Collected_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getReportPaginated($request){
        $query = DB::table('deliveries as del')
            ->select('del.*','cz.zone_name','s.name','p.plan_name','del.id as deli_id','merch.business_name','fs.flag_text',
                'td.is_hub','td.assign_to','td.flag_status_id','td.id as track_id',
                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS full_name')
            )
            ->leftjoin('courier_zones as cz','cz.id', '=', 'del.recipient_zone_id')
            ->leftjoin('stores as s','s.id', '=', 'del.store_id')
            ->leftjoin('plans as p','p.id', '=', 'del.plan_id')
            ->leftjoin('merchants as merch','merch.id', '=', 'del.merchant_id')
            ->join(DB::raw('(select * from tracking_details where in_out = 1 order by id desc) as td'),'td.deliveries_id', '=', 'del.id')
            ->join('flag_status as fs','fs.id', '=', 'del.status')
            ->groupBy('del.id')
            ->orderBy('del.id','desc');
            if (get_admin_hub_id() > 0) {
                $query = $query->where(['del.hub_id' => get_admin_hub_id()]);
            }

        return Datatables::of($query)
//            ->filterColumn('name', function($query, $keyword) {
//                $query->whereRaw("name like ?", ["%{$keyword}%"]);
//            })
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="#" onclick="viewProducts('.$user->deli_id.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Products"><i class="fa fa-cubes"></i></a>
                    <a href="'.route('admin.delivery.edit',array($user->deli_id)).'"  class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Products"><i class="fa fa-edit"></i></a>
                    ';
            })
            ->make(true);
    }

    public function exportFile($request)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $query = DB::table('members as m')
            ->select(
                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS Name'),
                'm.email as Email',
                'm.mobile_no as Mobile', 'm.username as Username', 'merch.is_get_commission as Get_commission', 'merch.status as Status', 'm.can_login as Can_login', 'merch.created_at as Joining_date')
            ->join('Collected as merch', 'merch.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'merch.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->leftJoin('national_id_cards as nid', 'nid.member_id', '=', 'm.id');
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('merch.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }


        $query = DB::table('members as m')
            ->select(DB::raw(
                'CONCAT(merch.first_name, " ", merch.last_name) as Name'),
                'm.email as Email',
                'm.mobile_no as Mobile',
                'm.username as Username',
                'merch.status as Status',
                'm.can_login as Canlogin',
                'r.role_name as Role',
                'merch.created_at as Joining_date'
            )->join('Collected as merch', 'merch.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'merch.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id');
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('merch.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }
        $data = $query->get();
        return $data;
    }

    public function getVehicleByMemberId($request, $id)
    {
        return  DB::table('Collected as merch')
            ->select('v.model', 'v.license_plate')
            ->join('vehicles as v', 'v.Plan_id', '=', 'merch.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getDriverByMemberId($request, $id)
    {
        return  DB::table('Collected as merch')
            ->select('d.first_name as driver_first_name', 'd.last_name as driver_last_name', 'dc.is_verified as license_vefication_status')
            ->join('drivers as d', 'd.Plan_id', '=', 'merch.id')
            ->leftJoin('driving_license as dc', 'dc.driver_id', '=', 'd.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getPlanList()
    {
        return ['' => 'Select Plan']
            + DB::table('Collected as a')
                ->select(DB::raw('CONCAT(a.first_name, " ", a.last_name, " - ", b.mobile_no ) AS Plan'), 'a.id')
                ->join('members as b', 'b.id', '=', 'a.member_id')
                ->orderBy('a.id', 'asc')
                // ->where(['b.is_active' => 1])
                ->lists('Plan', 'id');
    }

    public function getProducts($input)
    {
        return DB::table('Collected_product as dp')
            ->select('p.*','c.name as cat_name','s.name as store_name','dp.Collected_id','dp.is_approve')
            ->join('products as p', 'dp.product_id', '=', 'p.id')
            ->leftjoin('deliveries as d', 'dp.Collected_id', '=', 'd.id')
            ->leftjoin('categories as c', 'p.category_id', '=', 'c.id')
            ->leftjoin('stores as s', 'p.store_id', '=', 's.id')
            ->groupBy('dp.Collected_id','dp.product_id')
            ->where(['dp.Collected_id'=>$input['Collected_id']])
            ->get();
    }

    public function getAllProductByMerchantId($id){
        return  DB::table('products')
            ->select('id', 'name')
            ->where('merchant_id', $id)
            ->get();
    }
    public function getAllStoreByMerchantId($id){
        return  DB::table('stores')
            ->select('id', 'name')
            ->where('merchant_id', $id)
            ->get();
    }


    public function setApproval($input)
    {
        if (!empty($input['is_approve'])){
            foreach ($input['is_approve'] as $val){
                $changeStatus = CollectedProducts::where(['Collected_id'=>$input['Collected_id'],'product_id'=>$val])->first();
                $changeStatus->is_approve = 2;
                $changeStatus->save();
            }
            return 1;
        }
        return 0;
    }

    public function getRiders($input)
    {
        if ($input['is_hub'] == 2){
            return  DB::table('riders')
                ->select('id',DB::raw('CONCAT(first_name, " ", last_name) AS name'))
                ->get();
        }
        if ($input['is_hub'] == 1){
            return  DB::table('hub')
                ->select('id','hub_name AS name')
                ->get();
        }

    }

    public function getStatus()
    {
        if (get_admin_hub_id() > 0) {
            return  DB::table('flag_status')
                ->select('id','flag_text')
                ->whereIn('id',[2,3,10,11])
                ->get();
        }else{
            return  DB::table('flag_status')
                ->select('id','flag_text')
                ->get();
        }
    }

    public function trackingDetails($id)
    {
        return DB::table('tracking_details as td')
            ->select('td.*')
            ->leftjoin('flag_status as fg','fg.id','=','td.flag_status_id')
            ->where('td.deliveries_id',$id)
            ->get();
    }

    public function storeRiders($input)
    {
        switch ($input['flag_status_id']){
            case 1: $desc = 'Pending.';
            break;

            case 2: $desc = 'Your order has been accepted.';
            break;

            case 3: $desc = 'Your order is being sorted.';
            break;

            case 4: $desc = 'Your order has left the sorting facility.';
            break;

            case 5: $desc = 'Your order is in transit.';
            break;

            case 6: $desc = 'Your order has been delivered.';
            break;

            case 7: $desc = 'Your order has been RETURNED FROM HUB.';
            break;

            case 8: $desc = 'Your order has been marked for return.';
            break;

            case 9: $desc = 'Your order is being held at sorting.';
            break;

        }

        if ($input['flag_status_id'] == 10){
            $assign =   DB::table('riders')
                ->select(DB::raw('CONCAT(first_name, " ", last_name) AS name'),'id')
                ->where('id',$input['assign_to'])
                ->first();
            $desc = !empty($assign) ? 'Assigned to '.$assign->name."( ". $assign->id." )": '';
        }

        $assign_to = !empty($input['assign_to']) ? $input['assign_to'] : 0;
        $is_hub = !empty($input['is_hub']) ? $input['is_hub'] : 0;
        $hub_id = $input['flag_status_id'] == 4 ? 1 : get_admin_hub_id();
        foreach ($input['deliveried_id'] as $val){
            storeTrackingData(
                $deliveries_id = $val, $flag_status_id = $input['flag_status_id'],
                $assign_to, $is_hub, $notes = $input['notes'],
                $description = $desc,$in_out = 1, $hub_id
            );
        }

    }
}
