<?php namespace App\Repositories\Admin\Stores;
use App\DB\Admin\Stores;
use App\DB\Admin\Member;
use App\DB\Permission;
use DB;
use Datatables;


class EloquentStoresRepository implements StoresRepository
{
    protected $stores;

    function __construct(Stores $stores)
    {
        $this->stores = $stores;
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

    public function update($input, $id)
    {
        $stores = Stores::where('id', $id)->first();
        $stores->name   = $input['name'];
        $stores->updated_at   = date('Y-m-d H:i:s');
        if ($stores->save()) {
            return true;
        }
        return 0;
    }

    public function delete($id)
    {
        DB::table('Stores')
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
        return $this->stores->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $stores_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getReportPaginated($request){
        $query = DB::table('stores as s')
            ->select('s.*','z.zone_name','merch.business_name',
                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS full_name')
            )
            ->join('merchants as merch', 'merch.id', '=', 's.merchant_id')
            ->leftjoin('courier_zones as z', 'z.id', '=', 's.zone_id');
            if (get_admin_hub_id() > 0) {
                $query = $query->where('merch.hub_id', get_admin_hub_id());
            }

        return Datatables::of($query)
            ->filterColumn('name', function($query, $keyword) {
                $query->whereRaw("s.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('full_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(merch.first_name, merch.last_name) like ?", ["%{$keyword}%"]);
            })
//            ->addColumn('action_col', function ($user) {
//                return '
//                    <a href="'.route('admin.merchant.edit',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
//                    <a href="'.route('admin.merchant.delete',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="fa fa-trash"></i></a>';
//            })
            ->make(true);
    }

    public function store($input)
    {
        $stores = new Stores();
        $stores->name   = $input['name'];
        $stores->status     = 1;
        $stores->created_at   = date('Y-m-d H:i:s');
        if ($stores->save()) {
            return $stores->id;
        }
        return 0;
    }

    public function findOrThrowException($id)
    {
        return  DB::table('Stores')
            ->select('name','id')
            ->where('id', $id)
            ->first();
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
            ->join('Stores as merch', 'merch.member_id', '=', 'm.id')
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
            )->join('Stores as merch', 'merch.member_id', '=', 'm.id')
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
        return  DB::table('Stores as merch')
            ->select('v.model', 'v.license_plate')
            ->join('vehicles as v', 'v.Plan_id', '=', 'merch.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getDriverByMemberId($request, $id)
    {
        return  DB::table('Stores as merch')
            ->select('d.first_name as driver_first_name', 'd.last_name as driver_last_name', 'dc.is_verified as license_vefication_status')
            ->join('drivers as d', 'd.Plan_id', '=', 'merch.id')
            ->leftJoin('driving_license as dc', 'dc.driver_id', '=', 'd.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getPlanList()
    {
        return ['' => 'Select Plan']
            + DB::table('Stores as a')
                ->select(DB::raw('CONCAT(a.first_name, " ", a.last_name, " - ", b.mobile_no ) AS Plan'), 'a.id')
                ->join('members as b', 'b.id', '=', 'a.member_id')
                ->orderBy('a.id', 'asc')
                // ->where(['b.is_active' => 1])
                ->lists('Plan', 'id');
    }

    public function getAgentList()
    {
        // TODO: Implement getAgentList() method.
    }
}
