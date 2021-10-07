<?php namespace App\Repositories\Agent\AttendancePolicy;
use App\DB\Admin\Agent;
use App\DB\Admin\Member;
use App\DB\Agent\AttendancePolicy;
use App\DB\Agent\AttendancePolicyHead;
use App\DB\Permission;
use DB;
use Datatables;
use DateTime;
use App\DB\Admin\Role;


class EloquentAttendancePolicyRepository implements AttendancePolicyRepository
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

    public function update($input,$attendance_policy_head)
    {
        foreach ($input->day_name as $key=>$day){
            $ap = AttendancePolicy::find($input['row_id'][$key]);
            if (!empty($ap)) {
                $ap->day_name  = $input['day_name'][$key];
                $ap->in_time  = DateTime::createFromFormat('H:i A', $input['in_time'][$key])->format('H:i:s');
                $ap->working_hours  = DateTime::createFromFormat('H:i A', $input['working_hours'][$key])->format('H:i:s');
                $ap->delay_time  = $input['delay_time'][$key];
                $ap->extream_delay_time  = $input['extream_delay_time'][$key];
                $ap->break_time  = $input['break_time'][$key];
                $ap->working_type  = $input['working_type'][$key];
                $ap->attendence_head_id  = $attendance_policy_head;
                $ap->status   = 1;
                $ap->created_at   = date('Y-m-d H:i:s');
                $ap->save();
            }
        }
        if (!empty($attendance_policy_head)) {
            return $attendance_policy_head;
        }
        return 0;
    }

    public function delete($id)
    {
        $ap = AttendancePolicy::find($id);
        if (empty($ap)){
            redirect('outlet-category')->with('flashMessageError','Unable to delete attendance policy head');
        }
        $ap->status = 0;
        $ap->save();
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



    public function getReportPaginated($request, $per_page, $agent_id, $status = 1, $order_by = 'ap.id', $sort = 'asc')
    {
        $heads = AttendancePolicyHead::select('name','id',
            DB::raw("DATE_FORMAT(effective_from,'%M %d %Y %h:%i %p') AS effective_from "),
            'created_at')
            ->where('company_id',$this->company_id);
        return Datatables::of($heads)
            ->addColumn('action_col', function ($user) {
                return '
                    <a style="cursor:pointer" onclick="showHeadDetails('.$user->id.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Details"><i class="fa fa-expand"></i></a>
                    <a href="'.route('attendance.policy.edit',array($user->id,getLogedinAgentId())).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="'.route('attendance.policy.delete',array($user->id,getLogedinAgentId())).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete" onclick="return confirm(\'Are you sure, want to delete ?\')"><i class="fa fa-trash"></i></a>';
            })
            ->make(true);
    }

    public function getHeadDetails($head_id,$order_by = 'ap.id', $sort = 'asc'){
        $headDetails = DB::table('attendance_policy as ap')
            ->select(
                'ap.day_name',
                DB::raw("DATE_FORMAT(ap.in_time,'%h:%i %p') AS checkin_time "),
                DB::raw("DATE_FORMAT(ap.working_hours,'%h:%i %p') AS out_time "),
                'ap.delay_time','ap.extream_delay_time','ap.break_time',
                DB::raw("case  
                       when ap.working_type = 1 then \"Full Day\" 
                       when ap.working_type = 2 then \"Half Day\"
                       when ap.working_type = 0 then \"Week End\"
                    end as work_type")

            )
            ->join('attendance_policy_head as aph', 'ap.attendence_head_id', '=', 'aph.id')
            ->orderBy($order_by, $sort)
            ->where([ 'ap.status' => 1, 'ap.attendence_head_id'=>$head_id ]);
        return Datatables::of($headDetails)
            ->make(true);
    }

    public function store($input, $agent_id,$attendance_policy_head)
    {
        foreach ($input->day_name as $key=>$day){
            $ap = new AttendancePolicy();
            $ap->day_name  = $input['day_name'][$key];
            $ap->in_time  = DateTime::createFromFormat('H:i A', $input['in_time'][$key])->format('H:i:s');
            $ap->working_hours  = DateTime::createFromFormat('H:i A', $input['working_hours'][$key])->format('H:i:s');
            $ap->delay_time  = $input['delay_time'][$key];
            $ap->extream_delay_time  = $input['extream_delay_time'][$key];
            $ap->break_time  = $input['break_time'][$key];
            $ap->working_type  = $input['working_type'][$key];
            $ap->attendence_head_id  = $attendance_policy_head;
            $ap->status   = 1;
            $ap->created_at   = date('Y-m-d H:i:s');
            $ap->save();
        }

        if (!empty($attendance_policy_head)) {
            return $attendance_policy_head;
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
