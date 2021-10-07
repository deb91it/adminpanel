<?php namespace App\Repositories\Agent\Order;
use App\DB\Admin\Agent;
use App\DB\Admin\Member;
use App\DB\Agent\AttendancePolicy;
use App\DB\Agent\AttendancePolicyHead;
use App\DB\Api\Attendance;
use App\DB\Permission;
use App\Repositories\Agent\Order\OrderRepository;
use DB;
use Datatables;
use DateTime;
use App\DB\Admin\Role;


class EloquentOrderRepository implements OrderRepository
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

    public function updateAttendanceManually($input)
    {
        $status = '';
        $ap = Attendance::find($input['row_id']);
        if (!empty($ap)) {
            $ap->attendance_date        = date("Y-m-d");
            if (!empty($input['checkedin_time'])){
                $ap->checkedin_time        = date("H:i:s",strtotime($input['checkedin_time']));
                $status = 1;
            }
            if(!empty($input['checkedout_time'])){
                $ap->checkedout_time        = date("H:i:s",strtotime($input['checkedout_time']));
                $status = 0;
            }
            $ap->status         = $status;
            $ap->flag         = $input['flag'];
            $ap->given_by        = getLogedinAgentId();
            $ap->updated_at   = date('Y-m-d H:i:s');
            if ($ap->save()) {
                return $ap->id;
            }
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
    public function getEmployeeAttendanceDetails($request)
    {
        return Attendance::select('id', 'employee_id','flag',
            DB::raw("DATE_FORMAT(checkedin_time,'%h:%i %p') as checkedin_time"),
            DB::raw("DATE_FORMAT(checkedout_time,'%h:%i %p') as checkedout_time")
        )
            ->where(['employee_id' => $request->user_id, 'attendance_date' => date("Y-m-d")])
            ->first();
    }

    public function getReportPaginated($request, $per_page, $agent_id, $from, $to,$type, $status = 1)
    {
        $filter = $request->get('columns')[0]['search']['value'];
        if (!empty($filter)){
            $type = $filter;
        }
        $date_range = $request->get('columns')[7]['search']['value'];
        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (date_validate($start_date)  || date_validate($end_date)) {
                $from = $start_date;
                $to = $end_date;
            }
        }
        //echo $type."<br>".$from."<br>".$to;exit();

        $query=  DB::table('sr_outlet_order as order')
            ->select(
                'order.id','order.ordered_weight','m.unique_id','order.has_meeting','order.remarks',
                'order.ordered_amount','outlet.name','outlet.address','outlet.image_url',
                DB::raw('CONCAT(ag.first_name,SPACE(2), ag.last_name, "\n") as full_name'),
                DB::raw('DATE_FORMAT(order.created_at,\'%d-%m-%Y %h:%i %p\') as order_time'),
                DB::raw('DATE_FORMAT(order.meeting_time,\'%d-%m-%Y %h:%i %p\') as meeting_datetime')
            )
            ->join('outlets as outlet', 'outlet.id', '=', 'order.outlet_id')
            ->join('agents as ag', 'ag.id', '=', 'outlet.sr_id')
            ->join('members as m', 'ag.member_id', '=', 'm.id')
            ->where('order.company_id',get_agent_company_id());
        if (!empty($from)) {
            $query = $query->whereBetween('order.created_at', [$from,$to]);
        }
        if (!empty($type) && $type == 'has_meeting'){
            $query = $query->where('order.has_meeting', 1);
        }
        if (!empty($type) && $type == 'remarks'){
            $query = $query->where('order.remarks','!=' ,'');
        }

        return Datatables::of($query)
            ->filterColumn('full_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(ag.first_name, ag.last_name) like ?", ["%{$keyword}%"]);
                $query->orwhereRaw("m.unique_id like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->whereRaw("outlet.name like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action_col', function ($user) {
                return '
                    <a onclick="editAttendance('.$user->id.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a onclick="viewOrder('.$user->id.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="View Details"><i class="fa fa-expand"></i></a>

                    ';
            })
            ->make(true);
    }
    public function saveAttendanceManually($input)
    {
        $status = '';
        $ap = new Attendance();
        $ap->employee_id             = isset($input['user_id']) ? $input['user_id'] : 0;
        $ap->company_id        = get_agent_company_id();
        $ap->attendance_date        = date("Y-m-d");
        if (!empty($input['checkedin_time'])){
            $ap->checkedin_time        = date("H:i:s",strtotime($input['checkedin_time']));
            $status = 1;
        }
        if(!empty($input['checkedout_time'])){
            $ap->checkedout_time        = date("H:i:s",strtotime($input['checkedout_time']));
            $status = 0;
        }
        $ap->flag         = $input['flag'];
        $ap->status         = $status;
        $ap->is_holiday        = 0;
        $ap->given_by        = getLogedinAgentId();
        $ap->created_at    = date("Y-m-d H:i:s");
        if ($ap->save()) {
            return $ap->id;
        }
        return 0;
    }

    public function exportFile($request)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $query=  DB::table('agents as ag')
            ->select(
                'm.unique_id as m_employee_id',
                'ag.designation','ag.id','attend.attendance','attend.c_time','attend.o_time','attend.current_status'
            )
            ->join('members as m', 'ag.member_id', '=', 'm.id')
            ->leftjoin(DB::raw("(select employee_id,
                    DATE_FORMAT(attendance_date,'%M %d %Y') AS attendance,
                    DATE_FORMAT(checkedin_time,'%h:%i %p') as c_time,
                    DATE_FORMAT(checkedout_time,'%h:%i %p') as o_time, 
                     case  
                               when flag = 'P' then \"Present\" 
                               when flag = 'A' then \"Absent\"
                               when flag = 'L' then \"Leave\"
                               when flag = 'H' then \"Holiday\"
                               when flag = 'W' then \"Weekend\"
                               when flag = 'D' then \"Delay\"
                               when flag = 'E' then \"Ext delay\"
					end as current_status
                    from sr_attendence 
                    where attendance_date between "."'".$start_date."'"." AND "."'".$end_date."'".") as attend"),
                'ag.id', '=', 'attend.employee_id')
            ->where([ 'ag.company_id' => get_agent_company_id() ]);
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

    public function viewAttendanceDetails($user_id,$from_date,$to_date){
        $query=  DB::table('agents as ag')
            ->select(
                'm.unique_id as m_employee_id',
                'ag.designation','ag.id','attend.attendance','attend.attendance_date','attend.c_time','attend.o_time','attend.current_status'
            )
            ->join('members as m', 'ag.member_id', '=', 'm.id')
            ->leftjoin(DB::raw("(select employee_id,attendance_date,
                    DATE_FORMAT(attendance_date,'%M %d %Y') AS attendance,
                    DATE_FORMAT(checkedin_time,'%h:%i %p') as c_time,
                    DATE_FORMAT(checkedout_time,'%h:%i %p') as o_time, 
                     case  
                               when flag = 'P' then \"Present\" 
                               when flag = 'A' then \"Absent\"
                               when flag = 'L' then \"Leave\"
                               when flag = 'H' then \"Holiday\"
                               when flag = 'W' then \"Weekend\"
                               when flag = 'D' then \"Delay\"
                               when flag = 'E' then \"Ext delay\"
					end as current_status
                    from sr_attendence 
                    where employee_id=".$user_id." AND attendance_date between "."'".$from_date."'"." AND "."'".$to_date."'".") as attend"),
                'ag.id', '=', 'attend.employee_id')
            ->where([ 'ag.company_id' => get_agent_company_id(),'ag.id' => $user_id ]);
        $data = $query->get();
        return $data;
    }
}
