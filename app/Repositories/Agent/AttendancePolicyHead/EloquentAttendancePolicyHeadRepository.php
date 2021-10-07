<?php namespace App\Repositories\Agent\AttendancePolicyHead;
use App\DB\Admin\Agent;
use App\DB\Admin\Member;
use App\DB\Agent\AttendancePolicyHead;
use App\DB\Permission;
use DB;
use DateTime;
use Datatables;
use App\DB\Admin\Role;


class EloquentAttendancePolicyHeadRepository implements AttendancePolicyHeadRepository
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

    public function update($input)
    {
        $aph = AttendancePolicyHead::find($input['attendance_head_id']);
        if (!empty($aph)) {
            $aph->name  = $input['attendence_head_name'];
            $aph->effective_from  = DateTime::createFromFormat('d F Y - H:i', $input['effective_from'])->format('Y-m-d H:i:s');
            $aph->company_id  = get_agent_company_id();
            $aph->status   = 1;
            $aph->updated_by   = getLogedinAgentId();
            $aph->updated_at   = date('Y-m-d H:i:s');
            if ($aph->save()) {
                return $aph->id;
            }
        }
        return 0;
    }

    public function delete($id)
    {
        $aph = AttendancePolicyHead::find($id);
        if (empty($aph)){
            redirect('outlet-category')->with('flashMessageError','Unable to delete attendance policy head');
        }
        $aph->status = 0;
        $aph->deleted_at   = date('Y-m-d H:i:s');
        $aph->deleted_by   = getLogedinAgentId();
        $aph->save();
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



    public function getReportPaginated($request, $per_page, $agent_id, $status = 1, $order_by = 'aph.id', $sort = 'asc')
    {
        $query=  DB::table('attendance_policy_head as aph')
            ->select(
                'aph.*','c.name as company_name','c.logo_url as company_logo',
                DB::raw("DATE_FORMAT(aph.created_at,'%M %d %Y %h:%i %p') AS added_at "),
                DB::raw("DATE_FORMAT(aph.effective_from,'%M %d %Y') AS effective_at ")
            )->join('companies as c', 'aph.company_id', '=', 'c.id')
            ->orderBy($order_by, $sort)
            ->where([ 'aph.company_id' => $this->company_id, 'aph.status' => 1 ]);

        return Datatables::of($query)
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="'.route('attendance.policy.head.edit',array($user->id,getLogedinAgentId())).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="'.route('attendance.policy.head.delete',array($user->id,getLogedinAgentId())).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete" onclick="return confirm(\'Are you sure, want to delete ?\')"><i class="fa fa-trash"></i></a>';
            })
            ->make(true);
    }

    public function store($input, $agent_id)
    {
        $aph = new AttendancePolicyHead();
        $aph->name  = $input['attendence_head_name'];
        $aph->effective_from  = DateTime::createFromFormat('d F Y - H:i', $input['effective_from'])->format('Y-m-d H:i:s');
        $aph->company_id  = get_agent_company_id();
        $aph->status   = 1;
        $aph->created_by   = getLogedinAgentId();
        $aph->created_at   = date('Y-m-d H:i:s');
        if ($aph->save()) {
            return $aph->id;
        }
        return 0;
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
}
