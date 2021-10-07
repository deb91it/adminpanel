<?php namespace App\Repositories\Agent\Agent;
use App\DB\Admin\Agent;
use App\DB\Admin\Member;
use App\DB\Agent\EmployeeDetails;
use App\DB\Permission;
use DB;
use Datatables;
use App\DB\Admin\Role;


class EloquentAgentRepository implements AgentRepository
{
    protected $agent;
    protected $role;
    protected $company_id;

    function __construct(Agent $agent, Role $role)
    {
        $this->agent = $agent;
        $this->role = $role;
        $this->company_id = get_agent_company_id();
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
        $agent->designation = $input['designation'];
        $agent->parent_id   = $input['parent'];
        $agent->depth       = getAgentDepthLevel($input['parent']);
        $agent->company_id  = $this->company_id;
        $agent->language_id = $input['language'];
        $agent->zone_id     = $input['city'];
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
            ->update(['can_login' => 0, 'is_active' => 0]);

        DB::table('agents')
            ->where('member_id', $id)
            ->update([
                'status' => 0,
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => get_logged_user_id()
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
        return $this->agent->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $agent_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getReportPaginated($request, $per_page, $agent_id, $status = 1, $order_by = 'agn.parent_id', $sort = 'asc'){

        return DB::table('members as m')
            ->select(
                'm.id as id', 'm.email', 'm.mobile_no', 'm.can_login',
                DB::raw('CONCAT(agn.first_name, " ", agn.last_name) AS full_name'),
                'agn.company_id', 'agn.designation', 'agn.parent_id', 'agn.status',
                DB::raw('( SELECT CONCAT(first_name, " ", last_name, "<br> <span>", designation, " </span>") 
                    FROM agents WHERE id = agn.parent_id ) AS parent'),
                DB::raw('DATE_FORMAT(agn.created_at, "%d %M %Y %h:%i %p") as  created_at')
            )->join('agents as agn', 'agn.member_id', '=', 'm.id')
           // ->join('role_member as rm', 'rm.member_id', '=', 'agn.member_id')
           // ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->orderBy($order_by, $sort)
            ->where([ 'agn.company_id' => $this->company_id, 'agn.is_admin' => 0 ])
            ->paginate($per_page);
    }

    //Added Default Image
    public function store($input, $member_id, $agent_id)
    {
        $defaultImagePath = asset('backend/ezzyr_assets/app/media/img/no-image.png');
        $agent = new Agent();
        $agent->first_name  = $input['first_name'];
        $agent->last_name   = $input['last_name'];
        $agent->designation = $input['designation'];
        $agent->parent_id = $input['parent'];
        $agent->depth = getAgentDepthLevel($input['parent']);
        $agent->language_id = $input['language'];
        $agent->zone_id     = $input['city'];
        $agent->member_id   = $member_id;
        $agent->company_id   = $this->company_id;
        $agent->profile_pic_url   = $defaultImagePath;
        $agent->created_at   = date('Y-m-d H:i:s');
        $agent->created_by   = get_logged_user_id();
        if ($agent->save()) {
            return $agent->id;
        }
        return 0;
    }
    
    public function findOrThrowException($id, $agent_id)
    {
        return  DB::table('members as m')
            ->select($this->getSelectItemDuringEdit())
            ->join('agents as agn', 'agn.member_id', '=', 'm.id')
            ->leftJoin('role_member as rm', 'rm.member_id', '=', 'agn.member_id')
            ->leftJoin('roles as r', 'r.id', '=', 'rm.role_id')
            ->leftJoin('zones as z', 'z.zone_id', '=', 'agn.zone_id')
            ->leftJoin('countries as c', 'c.country_id', '=', 'z.country_id')
            ->leftJoin('languages as l', 'l.id', '=', 'agn.language_id')
            ->where(['m.id' => $id])
            ->first();
    }

    protected function getSelectItemDuringEdit()
    {
        return [
            'm.id as member_id', 'm.username','m.unique_id', 'm.email', 'm.mobile_no', 'm.can_login', 'm.is_active',
            'agn.id','agn.first_name', 'agn.last_name','agn.designation','agn.parent_id',
            'r.id as role_id', 'r.role_name',
            'z.zone_id', 'z.name as zone_name',
            'c.country_id', 'c.name as country_name',
            'l.id as language_id', 'l.language_name'
        ];
    }

    public function exportFile($request, $agent_id)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        DB::setFetchMode(\PDO::FETCH_ASSOC);

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
            )->join('agents as agn', 'agn.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'agn.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->where(['agn.parent_id' => $agent_id]);
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('agn.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }
        $data = $query->get();
        return $data;
    }

    public function updateProfile($input, $id)
    {
        $adminUser = Agent::where('member_id', $id)->first();

        if ($input->hasfile('profile_pic')) {
            $save_path = public_path('resources/profile_pic/');
            $file = $input->file('profile_pic');
            $image_name = $input['first_name']."-".$input['last_name']."-".time()."-".$file->getClientOriginalName();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();

            //Delete existing image
            if (\File::exists($save_path.$adminUser->profile_pic))
            {
                \File::delete($save_path.$adminUser->profile_pic);
            }

            //Update DB Field
            $adminUser->profile_pic      = $image_name;
            $adminUser->pic_mime_type    = $image_mime;
        }

        $adminUser->first_name  = $input['first_name'];
        $adminUser->last_name   = $input['last_name'];
        $adminUser->gender      = $input['gender'];
        $adminUser->updated_at  = date('Y-m-d H:i:s');

        if ($adminUser->save()) {
            $member = Member::where('id', $id)->first();
            $member->email = $input['email'];
            $member->mobile_no = $input['mobile_no'];
            $member->username    = $input['username'];
            if ($member->save()) {
                return true;
            }
        }
        return false;
    }

    public function getCompanyRoleList($company_id)
    {
        return ['' => 'Select Role'] + $this->role->where('company_id', $company_id)->orderBy('id', 'asc')->lists('role_name', 'id')->toArray();
    }

    public function getCompanySalesPersonList($company_id)
    {
        return ['' => 'Select Parent']
            + DB::table('agents as a')
                ->select(DB::raw('CONCAT(a.first_name, " ", a.last_name, " [", r.role_name, "]" ) AS agent'), 'a.id as agent_id')
                ->join('members as m', 'm.id', '=', 'a.member_id')
                ->join('role_member as rm', 'rm.member_id', '=', 'm.id')
                ->join('roles as r', 'r.id', '=', 'rm.role_id')
                ->orderBy('a.id', 'asc')
                ->where(['a.company_id' => $company_id, 'a.is_admin' => 0 ])
                ->where('a.depth', '<', 5)
                ->lists('agent', 'agent_id');
    }

    public function getEmployeeDetails($request){
        return DB::table('employee_details')
            ->select('*')
            ->where('member_id',$request->member_id)
            ->first();
    }

    public function updateEmployeeDetails($request){
        $data = EmployeeDetails::find($request->row_id);
        $data->father_name = $request->father_name;
        $data->mother_name = $request->mother_name;
        $data->address = $request->address;
        $data->nid = $request->nid;
        $data->birth_certificate_no = $request->birth_certificate_no;
        $data->date_of_join = $request->date_of_join;
        $data->employee_id = $request->employee_id;
        $data->member_id = $request->member_id;
        $data->resign_date = $request->resign_date;
        if ($data->save()){
            return $data->id;
        }
        return 0;
    }

    public function saveEmployeeDetails($request){
        $data = new EmployeeDetails();
        $data->father_name = $request->father_name;
        $data->mother_name = $request->mother_name;
        $data->address = $request->address;
        $data->nid = $request->nid;
        $data->birth_certificate_no = $request->birth_certificate_no;
        $data->date_of_join = $request->date_of_join;
        $data->employee_id = $request->employee_id;
        $data->member_id = $request->member_id;
        $data->resign_date = $request->resign_date;
        if ($data->save()){
            return $data->id;
        }
        return 0;

    }

    public function getOrganogramData(){
        return DB::table('agents as a')
            ->select(
                'a.id as key',
                'm.unique_id as employee_id',
                DB::raw('CONCAT(a.first_name, " ", a.last_name) AS name'),
                'a.profile_pic_url as image',
                'a.designation',
                'a.parent_id as parent'
            )
                ->join('members as m', 'm.id', '=', 'a.member_id')
                ->orderBy('a.id', 'asc')
                ->where([ 'a.company_id' => $this->company_id ])
                ->where('a.depth', '!=', 0)
                ->get('agent', 'agent_id');
    }
}
