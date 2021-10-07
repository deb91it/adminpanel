<?php

namespace App\Http\Controllers\Agent;

use App\DB\Agent\AttendancePolicy;
use App\DB\Agent\AttendancePolicyHead;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\AttendancePolicy\AttendancePolicyRepository;
use App\Repositories\Agent\AttendancePolicyHead\AttendancePolicyHeadRepository;
use App\Repositories\Agent\Agent\AgentRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Http\Requests\Agent\AttendancePolicyRequest;
use Excel;
use DB;
use Validator;
use Illuminate\Support\Facades\Hash;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class AttendancePolicyController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $agent;
    protected $attendance_policy;
    protected $attendance_policy_head;
    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;
    protected $agent_id;
    protected $company_id;

    /**
     * AgentController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        AgentRepository $agent
        , AttendancePolicyRepository $attendance_policy
        , AttendancePolicyHeadRepository $attendance_policy_head
        , RoleRepository $roles
        , MemberRepository $member
    )
    {
        $this->agent = $agent;
        $this->attendance_policy = $attendance_policy;
        $this->attendance_policy_head = $attendance_policy_head;
        $this->roles = $roles;
        $this->member = $member;
        $this->agent_id = getLogedinAgentId();
        $this->company_id = get_agent_company_id();
    }

    public function index(Request $request)
    {
        $per_page = 25;
        return view('agent.attendance-policy.index')
        ->withAgents($this->attendance_policy->getReportPaginated($request, $per_page, $this->company_id));
    }

    public function getDataTableReport(Request $request){
        $per_page = 25;
        return $this->attendance_policy->getReportPaginated($request,$per_page, $this->company_id);
    }



    public function create()
    {
        $days = [
            'Saturday','Sunday',
            'Monday','Tuesday',
            'Wednesday','Thursday',
            'Friday'
        ];
        return view('agent.attendance-policy.create',compact('days'));
    }

    public function store(AttendancePolicyRequest $request)
    {
        //print_r($request->day_name);exit();
        $attendance_policy_head = $this->attendance_policy_head->store($request, $this->agent_id);
        if ($attendance_policy_head == 0){
            return redirect('attendance-policy/create')->with('flashMessageError','Unable to create attendance policy');
        }
        $attendance_policy = $this->attendance_policy->store($request, $this->agent_id,$attendance_policy_head);
        if ($attendance_policy > 0) {
            return redirect('attendance-policy/create')->with('flashMessageSuccess','The attendance policy has successfully created !');
        }
        return redirect('attendance-policy/create')->with('flashMessageError','Unable to create attendance policy');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id,$agent_id)
    {
        $this->checkAgent($agent_id);
        $head = AttendancePolicyHead::find($id);
        $formData = DB::table('attendance_policy as policy')
            ->select('policy.*','heads.name as attendence_head_name','heads.effective_from')
            ->join('attendance_policy_head as heads','policy.attendence_head_id', '=', 'heads.id')
            ->where('policy.attendence_head_id',$id)
            ->get();
        if (empty($formData)){
            return redirect('attendance-policy')->with('flashMessageError','Invalid id.');
        }
        return view('agent.attendance-policy.edit',compact('formData','head'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttendancePolicyRequest $request)
    {
        $attendance_policy_head = $this->attendance_policy_head->update($request);
        if ($attendance_policy_head == 0){
            return redirect('attendance-policy/create')->with('flashMessageError','Unable to create attendance policy');
        }
            $agent = $this->attendance_policy->update($request,$attendance_policy_head);
            if ($agent) {
                return redirect('attendance-policy')->with('flashMessageSuccess','The attendance policy successfully updated.');
            }
        return redirect('attendance-policy')->with('flashMessageError','Unable to updated attendance policy');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$agent_id)
    {
        $this->checkAgent($agent_id);
        $this->attendance_policy->delete($id);
        return redirect('attendance-policy')->with('flashMessageSuccess','The attendance policy  successfully deleted.');
    }
    public function checkAgent($agent_id){
        if ($agent_id != getLogedinAgentId()){
            return redirect('attendance-policy')->with('flashMessageError','Invalid action');
        }
        return true;
    }

    public function viewHeadDetails($head_id){
        return $this->attendance_policy->getHeadDetails($head_id);
    }
    

}
