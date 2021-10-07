<?php namespace App\Repositories\Admin\Agent;
use App\DB\Admin\Agent;
use App\DB\Admin\Member;
use App\DB\Permission;
use DB;
use Datatables;


class EloquentAgentRepository implements AgentRepository
{
    protected $agent;

    function __construct(Agent $agent)
    {
        $this->agent = $agent;
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
        $agent = Agent::where('member_id', $id)->first();
        $agent->first_name  = $input['first_name'];
        $agent->last_name   = $input['last_name'];
        $agent->hub_id = $input['hub_id'];
        $agent->nid     = $input['nid'];
        $agent->zone_id     = $input['zone_id'];
        $agent->status      = (isset($input['is_active']) && $input['is_active'] == 1) ? 1 : 0;
        $agent->updated_at  = date('Y-m-d H:i:s');
        $agent->edited_by   = get_logged_user_id();
        if ($agent->save()) {
            return true;
        }
        return 0;
    }

    public function delete($id)
    {
        DB::table('members')
            ->where('id', $id)
            ->delete();

        DB::table('riders')
            ->where('member_id', $id)
            ->delete();
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
        return $this->agent->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $agent_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getReportPaginated($request){

        $start_date = '';
        $end_date = '';
        $date_range = $request->get('columns')[9]['search']['value'];

        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (! date_validate($start_date)  || ! date_validate($end_date)) {
                $start_date = '';
                $end_date = '';
            }
        }

        $query = DB::table('members as m')
            ->select('m.id as id', 'm.email as m_email','h.hub_name',
                DB::raw('CONCAT(agn.first_name, " ", agn.last_name) AS full_name'),
                'm.mobile_no as m_mobile',
                'm.username as m_username',
                'm.unique_id',
                'agn.status as agn_status',
                'm.can_login as m_canlogin',
                'r.role_name as r_rolename',
                'cz.zone_name as rider_zone',
                'agn.created_at as joining_date'
            )->join('riders as agn', 'agn.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'agn.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->join('hub as h', 'h.id', '=', 'agn.hub_id')
            ->Leftjoin('courier_zones as cz', 'cz.id', '=', 'agn.zone_id')
            ;
           // ->leftJoin('national_id_cards as nid', 'nid.member_id', '=', 'm.id');
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('agn.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }
        if (get_admin_hub_id() > 0) {
            $query = $query->where('agn.hub_id', get_admin_hub_id());
        }

        return Datatables::of($query)
            ->filterColumn('full_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(agn.first_name, agn.last_name) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('hub_name', function($query, $keyword) {
                $query->whereRaw("h.hub_name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('m_email', function($query, $keyword) {
                $query->whereRaw("m.email like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('m_mobile', function($query, $keyword) {
                $query->whereRaw("m.mobile_no like ?", ["%{$keyword}%"]);
            })

            ->addColumn('action_col', function ($user) {
                return '
                    <a href="'.route('admin.agent.edit',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="'.route('admin.agent.delete',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="fa fa-trash"></i></a>
                    <a href="'.route('admin.member.profile',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Profile"><i class="fa fa-address-card-o"></i></a>
                    <a href="'.route('admin.agent.pickup.list',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Today Pickup Lists"><i class="fa fa-map-pin"></i></a>
                    <a href="'.route('admin.agent.delivery.list',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Today Delivery Lists"><i class="fa fa-map-marker"></i></a>
                    ';
            })
            ->make(true);
    }

    public function store($input, $member_id)
    {
        $agent = new Agent();
        $agent->first_name   = $input['first_name'];
        $agent->last_name    = $input['last_name'];
        // $agent->gender    = $input['gender'];
        $agent->hub_id = $input['hub_id'];
        $agent->nid     = $input['nid'];
        $agent->zone_id     = $input['zone_id'];
        $agent->member_id    = $member_id;
        $agent->created_at   = date('Y-m-d H:i:s');
        $agent->created_by   = get_logged_user_id();
        if ($agent->save()) {
            return $agent->id;
        }
        return 0;
    }
    
    public function findOrThrowException($id)
    {
        return  DB::table('members as m')
            ->select($this->getSelectItemDuringEdit())
            ->join('riders as agn', 'agn.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'agn.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
           // ->leftJoin('vehicles as v', 'v.agent_id', '=', 'agn.id')
            ->where('m.id', $id)
            ->first();
    }

    protected function getSelectItemDuringEdit()
    {
        return [
            'm.id as member_id', 'm.username', 'm.email', 'm.mobile_no', 'm.can_login', 'm.is_active',
            'agn.id','agn.first_name', 'agn.last_name', 'agn.profile_pic',
            'r.id as role_id', 'r.role_name',
            'agn.profile_pic','agn.nid','agn.hub_id','agn.zone_id'
         //   DB::raw('(SELECT COUNT(id) FROM vehicles WHERE agent_id = agn.id) as no_of_vehicle'),
          //  DB::raw('(SELECT COUNT(id) FROM drivers WHERE agent_id = agn.id) as no_of_driver')
        ];
    }

    public function exportFile($request)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        DB::setFetchMode(\PDO::FETCH_ASSOC);
        $query = DB::table('members as m')
            ->select(
                DB::raw('CONCAT(agn.first_name, " ", agn.last_name) AS Name'),
                'm.email as Email',
                'm.mobile_no as Mobile', 'm.username as Username', 'agn.is_get_commission as Get_commission', 'agn.status as Status', 'm.can_login as Can_login', 'agn.created_at as Joining_date')
            ->join('riders as agn', 'agn.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'agn.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->leftJoin('national_id_cards as nid', 'nid.member_id', '=', 'm.id');
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('agn.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }


        $query = DB::table('members as m')
            ->select(DB::raw(
                'CONCAT(agn.first_name, " ", agn.last_name) as Name'),
                'm.email as Email',
                'm.mobile_no as Mobile',
                'm.username as Username',
                'agn.status as Status',
                'm.can_login as Canlogin',
                'r.role_name as Role',
                'agn.created_at as Joining_date'
            )->join('riders as agn', 'agn.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'agn.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id');
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('agn.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }
        $data = $query->get();
        return $data;
    }

    public function getVehicleByMemberId($request, $id)
    {
        return  DB::table('riders as agn')
            ->select('v.model', 'v.license_plate')
            ->join('vehicles as v', 'v.agent_id', '=', 'agn.id')
            ->where('agn.member_id', $id)
            ->get();
    }

    public function getDriverByMemberId($request, $id)
    {
        return  DB::table('riders as agn')
            ->select('d.first_name as driver_first_name', 'd.last_name as driver_last_name', 'dc.is_verified as license_vefication_status')
            ->join('drivers as d', 'd.agent_id', '=', 'agn.id')
            ->leftJoin('driving_license as dc', 'dc.driver_id', '=', 'd.id')
            ->where('agn.member_id', $id)
            ->get();
    }

    public function getAgentList()
    {
        return ['' => 'Select agent']
            + DB::table('agents as a')
                ->select(DB::raw('CONCAT(a.first_name, " ", a.last_name, " - ", b.mobile_no ) AS agent'), 'a.id')
                ->join('members as b', 'b.id', '=', 'a.member_id')
                ->orderBy('a.id', 'asc')
                // ->where(['b.is_active' => 1])
                ->lists('agent', 'id');
    }

    public function getPickupList($request, $per_page = 20) {

        $start_date = $request['from_date'];
        $end_date = $request['to_date'];
        $date_range = $request->get('columns')[3]['search']['value'];

        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (! date_validate($start_date)  || ! date_validate($end_date)) {
                $start_date = '';
                $end_date = '';
            }
        }
        $flag_status = array(1,11);
        if (isset($request['flag_status']) && !empty($request['flag_status']))
        {
            $flag_status = array($request['flag_status']);
        }
        $rows =  DB::table('deliveries as d')
            ->select(

                'ms.business_name as merchant_name',
                'ms.address as merchant_address',
                'ms.cod_percentage as merchant_cod_percentage',
                'm.email as merchant_email',
                'm.mobile_no as merchant_contact',
                'd.*',
                'fs.flag_text as status_text', 'fs.color_code as status_color',
                's.name as store_name',
                'p.plan_name',
                'cz.zone_name as recipient_zone_name',
                DB::raw('(SELECT SUM(weight) FROM products WHERE id IN (SELECT product_id FROM delivery_product WHERE delivery_id = d.id) ) AS consignment_weight')

            )
            ->join('merchants as ms', 'ms.id', '=', 'd.merchant_id')
            ->join('members as m', 'm.id', '=', 'ms.member_id')
            ->join('flag_status as fs', 'fs.id', '=', 'd.status')
            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
            ->where([ 'd.recipient_zone_id' => $request['zone_id'] ]);
        if (!empty($request['from_date']) && !empty($request['to_date']))
        {
            $rows = $rows->whereBetween( 'd.created_at', [$start_date." 00:00:01",$end_date." 23:59:59"] );
        }
        $rows = $rows->whereIN('d.status',$flag_status);
        $rows = $rows->orderBy('d.id', 'desc');


        return Datatables::of($rows)
            ->filterColumn('merchant_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(ms.first_name, ms.last_name) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('consignment_id', function($query, $keyword) {
                $query->whereRaw("consignment_id like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getDeliveryLists($request, $per_page =20) {

        $extension_query = '';
        $date_range = $request->get('columns')[3]['search']['value'];
        $start_date = $request["from_date"];
        $end_date = $request["to_date"];
        $flag_status = array(6,7,8,9,10);


        if (isset($request['flag_status']) && !empty($request['flag_status']))
        {
            $flag_status = array($request['flag_status']);
        }

        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (! date_validate($start_date)  || ! date_validate($end_date)) {
                $start_date = '';
                $end_date = '';
            }
        }

        if (!empty($start_date) && !empty($end_date))
        {
            $extension_query = ' created_at between "'.$start_date." 00:00:01".'" AND "'.$end_date." 23:59:59".'" AND ';
        }
        $rows =  DB::table('deliveries as d')
            ->select(
                'ms.business_name as merchant_name',
                'ms.address as merchant_address',
                'ms.cod_percentage as merchant_cod_percentage',
                'm.email as merchant_email',
                'm.mobile_no as merchant_contact',
                'd.*',
                'fs.flag_text as status_text', 'fs.color_code as status_color',
                's.name as store_name',
                'p.plan_name','p.charge as plan_charge',
                'cz.zone_name as recipient_zone_name',
                DB::raw('(SELECT SUM(weight) FROM products WHERE id IN (SELECT product_id FROM delivery_product WHERE delivery_id = d.id) ) AS consignment_weight')
            )
            ->join('flag_status as fs', 'fs.id', '=', 'd.status')
            ->join(DB::raw('
            (
            select * from tracking_details 
            where '.$extension_query.'
                assign_to = '.$request["user_id"].'
            AND
                flag_status_id = '.$request["flag_status_id"].'
            AND
                is_hub = '.$request["is_hub"].'
            AND
                is_active = 1
            ) as td'), 'd.id', '=', 'td.deliveries_id')
            ->join('merchants as ms', 'ms.id', '=', 'd.merchant_id')
            ->join('members as m', 'm.id', '=', 'ms.member_id')
            ->leftJoin('stores as s', 's.id', '=', 'd.store_id')
            ->leftJoin('plans as p', 'p.id', '=', 'd.plan_id')
            ->leftJoin('courier_zones as cz', 'cz.id', '=', 'd.recipient_zone_id')
        ;

        $rows = $rows->whereIN('d.status',$flag_status);
        $rows = $rows->groupBy('d.id', 'td.assign_to','td.flag_status_id');
        $rows = $rows->orderBy('d.id', 'desc');

        return Datatables::of($rows)
            ->filterColumn('merchant_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(ms.first_name, ms.last_name) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('consignment_id', function($query, $keyword) {
                $query->whereRaw("consignment_id like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }
}
