<?php namespace App\Repositories\Admin\PlansAssign;
use App\DB\Admin\Plans;
use App\DB\Admin\PlansAssign;
use App\DB\Admin\Member;
use App\DB\Permission;
use DB;
use Datatables;


class EloquentPlansAssignRepository implements PlansAssignRepository
{
    protected $plansassign;

    function __construct(PlansAssign $plansassign)
    {
        $this->plans_assign = $plansassign;
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
        $plans = $input['plan_id'];
        if ($input->has('returned_plan_id'))
        {
            $plans = array_merge($input['plan_id'],$input['returned_plan_id']);
        }
        $query = DB::table('plan_assign_to_merchant')
            ->select('*')
            ->where(['merchant_id'=> $id,'status'=>1])
            ->get();
        if(!empty($query)){
            foreach ($query as $value){
                $plansassign = PlansAssign::find($value->id);
                $plansassign->status     = 0;
                $plansassign->save();

            }
        }
        foreach ($plans as $value){
            $plansassign = new PlansAssign();
            $plansassign->plan_id   = $value;
            $plansassign->merchant_id   = $input['merchant_id'];
            $plansassign->status     = 1;
            $plansassign->created_at   = date('Y-m-d H:i:s');
            $plansassign->save();

        }

        return 1;
    }

    public function delete($id)
    {
        $query = DB::table('plan_assign_to_merchant')
            ->select('*')
            ->where('merchant_id', $id)
            ->get();
        foreach ($query as $value){
            $plansassign = PlansAssign::find($value->id);
            $plansassign->status     = 0;
            $plansassign->save();

        }

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
        return $this->plans_assign->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $plansassign_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getReportPaginated($request){


        $query = DB::table('members as m')
            ->select('m.id as id', 'm.email as m_email',
                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS full_name'),
                'm.mobile_no as m_mobile',
                'm.username as m_username',
                'm.unique_id',
                'merch.status as merch_status',
                'm.can_login as m_canlogin',
                'r.role_name as r_rolename',
                'merch.created_at as joining_date','merch.id as merchant_id','pa.status as has_plan','pa.id as pa_plan'
            )->join('merchants as merch', 'merch.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'merch.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->leftjoin(DB::raw("(select * from plan_assign_to_merchant
                    where status = 1 ORDER BY id DESC) as pa"), 'pa.merchant_id', '=', 'merch.id')
            ->groupBy('merch.id')
        ;

        return Datatables::of($query)
            ->filterColumn('full_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(merch.first_name, \" \", merch.last_name) AS full_name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('plan_name', function($query, $keyword) {
                $query->whereRaw("plan_name like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="'.route('admin.plan-assign.edit',array($user->merchant_id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="'.route('admin.plan-assign.delete',array($user->merchant_id)).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="fa fa-trash"></i></a>
                    <a href="#" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" onclick="viewPlans('.$user->merchant_id.')" title="View Plan"><i class="fa fa-tasks"></i></a>
                    ';
            })
            ->make(true);
    }

    public function store($input)
    {
        foreach ($input['plan_id'] as $value){
            $plansassign = new PlansAssign();
            $plansassign->plan_id   = $value;
            $plansassign->merchant_id   = $input['merchant_id'];
            $plansassign->status     = 1;
            $plansassign->created_at   = date('Y-m-d H:i:s');
            $plansassign->save();

        }
        return 0;
    }

    public function viewPlan($input)
    {
        return DB::table('plan_assign_to_merchant as pa')
            ->select('p.plan_name','pa.created_at as createTime','pa.status')
            ->leftjoin('plans as p', 'p.id', '=', 'pa.plan_id')
            ->where(['pa.merchant_id'=> $input['plan_assign_id'],'pa.status'=>1])
            ->get();
    }

    public function findOrThrowException($id, $type)
    {
        return DB::table('plan_assign_to_merchant as pa')
            ->select($this->getSelectItemDuringEdit())
            ->join('merchants as merch', 'merch.id', '=', 'pa.merchant_id')
            ->join('plans as p', 'p.id', '=', 'pa.plan_id')
            ->where('pa.merchant_id', $id)
            ->where('pa.status', 1)
            ->where('p.plan_type', $type)
            ->get();
    }

    public function getPlannedByType($type)
    {
        // TODO: Implement getPlannedByType() method.
        return Plans::select('plan_name','id')->where(['status' => 1, 'plan_type' => $type])->get();
    }

    protected function getSelectItemDuringEdit()
    {
        return [
            'pa.*','p.plan_name','merch.id as merchant_id',
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
            ->join('PlansAssign as merch', 'merch.member_id', '=', 'm.id')
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
            )->join('PlansAssign as merch', 'merch.member_id', '=', 'm.id')
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
        return  DB::table('PlansAssign as merch')
            ->select('v.model', 'v.license_plate')
            ->join('vehicles as v', 'v.Plan_id', '=', 'merch.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getDriverByMemberId($request, $id)
    {
        return  DB::table('PlansAssign as merch')
            ->select('d.first_name as driver_first_name', 'd.last_name as driver_last_name', 'dc.is_verified as license_vefication_status')
            ->join('drivers as d', 'd.Plan_id', '=', 'merch.id')
            ->leftJoin('driving_license as dc', 'dc.driver_id', '=', 'd.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getPlanList()
    {
        return ['' => 'Select Plan']
            + DB::table('PlansAssign as a')
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
