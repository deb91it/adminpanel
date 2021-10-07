<?php namespace App\Repositories\Admin\CourierZones;
use App\DB\Admin\CourierZones;
use App\DB\Admin\Member;
use App\DB\Permission;
use DB;
use Datatables;


class EloquentCourierZonesRepository implements CourierZonesRepository
{
    protected $plan;

    function __construct(CourierZones $plan)
    {
        $this->plan = $plan;
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
        $plan = CourierZones::where('id', $id)->first();
        if ($plan->zone_code   == 0)
        {
            $plan->zone_code   = strtoupper(getUniqueZoneCode(8));
        }
        $plan->zone_name   = $input['zone_name'];
        $plan->updated_at   = date('Y-m-d H:i:s');
        if ($plan->save()) {
            return true;
        }
        return 0;
    }

    public function delete($id)
    {
        DB::table('courier_zones')
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
        return $this->plan->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $plan_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getReportPaginated($request){
        $query = DB::table('courier_zones as cz')
            ->select('cz.*'
            );

        return Datatables::of($query)
            ->filterColumn('zone_name', function($query, $keyword) {
                $query->whereRaw("zone_name like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="'.route('admin.courier_zone.edit',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="'.route('admin.courier_zone.delete',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="fa fa-trash"></i></a>
                    ';
            })
            ->make(true);
    }

    public function store($input)
    {
        $plan = new CourierZones();
        $plan->zone_name   = $input['zone_name'];
        $plan->zone_code   = strtoupper(getUniqueZoneCode(8));
        $plan->status     = 1;
        $plan->created_at   = date('Y-m-d H:i:s');
        if ($plan->save()) {
            return $plan->id;
        }
        return 0;
    }

    public function findOrThrowException($id)
    {
        return  DB::table('courier_zones')
            ->select('zone_name','id')
            ->where('id', $id)
            ->first();
    }

    protected function getSelectItemDuringEdit()
    {
        return [
            'p.*',
            DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS full_name'),
        ];
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
            ->join('CourierZones as merch', 'merch.member_id', '=', 'm.id')
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
            )->join('CourierZones as merch', 'merch.member_id', '=', 'm.id')
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
        return  DB::table('CourierZones as merch')
            ->select('v.model', 'v.license_plate')
            ->join('vehicles as v', 'v.Plan_id', '=', 'merch.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getDriverByMemberId($request, $id)
    {
        return  DB::table('CourierZones as merch')
            ->select('d.first_name as driver_first_name', 'd.last_name as driver_last_name', 'dc.is_verified as license_vefication_status')
            ->join('drivers as d', 'd.Plan_id', '=', 'merch.id')
            ->leftJoin('driving_license as dc', 'dc.driver_id', '=', 'd.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getPlanList()
    {
        return ['' => 'Select Plan']
            + DB::table('CourierZones as a')
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
