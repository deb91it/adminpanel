<?php

namespace App\Http\Controllers\Agent;

use App\DB\Agent\AttendancePolicyHead;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\AttendancePolicyHead\AttendancePolicyHeadRepository;
use App\Repositories\Agent\Agent\AgentRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;

use App\Http\Requests\Agent\AttendancePolicyHeadRequest;
use Excel;
use DB;
use Validator;
use Illuminate\Support\Facades\Hash;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class AttendancePolicyHeadController extends Controller
{
    /**
     * @var
     */
    protected $_errors;
    protected $agent;
    protected $attendance_policy_head;
    protected $roles;
    /**
     * @var MemberRepository
     */
    protected $member;
    protected $agent_id;
    protected $company_id;
    /**
     * AgentController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        AgentRepository $agent
        , AttendancePolicyHeadRepository $attendance_policy_head
        , RoleRepository $roles
        , MemberRepository $member
        )
    {
        $this->agent = $agent;
        $this->attendance_policy_head = $attendance_policy_head;
        $this->roles = $roles;
        $this->member = $member;
        $this->agent_id = getLogedinAgentId();
        $this->company_id = get_agent_company_id();
    }

    public function index(Request $request)
    {
        $per_page = 25;
        return view('agent.attendance-policy-head.index')
        ->withAgents($this->attendance_policy_head->getReportPaginated($request, $per_page, $this->company_id));
    }

    public function getDataTableReport(Request $request){
        $per_page = 25;
        return $this->attendance_policy_head->getReportPaginated($request,$per_page, $this->company_id);
    }



    public function create()
    {
        return view('agent.attendance-policy-head.create');
    }

    public function store(AttendancePolicyHeadRequest $request)
    {
        $attendance_policy_head = $this->attendance_policy_head->store($request, $this->agent_id);
        if ($attendance_policy_head > 0) {
            return redirect('attendance-policy-head/create')->with('flashMessageSuccess','The attendance policy head has successfully created !');
        }
        return redirect('attendance-policy-head/create')->with('flashMessageError','Unable to create attendance policy head');
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
        $formData = AttendancePolicyHead::find($id);
        if (empty($formData)){
            return redirect('attendance-policy-head')->with('flashMessageError','Invalid id.');
        }
        return view('agent.attendance-policy-head.edit',compact('formData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttendancePolicyHeadRequest $request)
    {
        //echo $request->effective_from;exit();
            $agent = $this->attendance_policy_head->update($request);
            if ($agent) {
                return redirect('attendance-policy-head')->with('flashMessageSuccess','The attendance policy head successfully updated.');
            }
        return redirect('attendance-policy-head')->with('flashMessageError','Unable to updated attendance policy head');
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
        $this->attendance_policy_head->delete($id);
        return redirect('attendance-policy-head')->with('flashMessageSuccess','The attendance policy head successfully deleted.');
    }
    public function checkAgent($agent_id){
        if ($agent_id != getLogedinAgentId()){
            return redirect('attendance-policy-head')->with('flashMessageError','Invalid action');
        }
        return true;
    }
    

}
