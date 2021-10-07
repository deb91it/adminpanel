<?php

namespace App\Http\Controllers\Agent;

use App\DB\Agent\Agent;
use App\DB\Agent\OutletCategories;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\OutletCategory\OutletCategoryRepository;
use App\Repositories\Agent\Agent\AgentRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Agent\OutletCategoryRequest;
use App\Http\Requests\Agent\AgentEditRequest;
use App\Http\Requests\Agent\EditProfile;
use Excel;
use DB;
use Validator;
use Illuminate\Support\Facades\Hash;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class OutletCategoryController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $agent;
    protected $outlet;

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
        , OutletCategoryRepository $outlet
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->agent = $agent;
        $this->outlet = $outlet;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
        $this->agent_id = getLogedinAgentId();
        $this->company_id = get_agent_company_id();
    }

    public function index(Request $request)
    {
        $per_page = 25;
        return view('agent.outlet-category.index')
        ->withAgents($this->outlet->getReportPaginated($request, $per_page, $this->company_id));
    }

    public function getDataTableReport(Request $request){
        $per_page = 25;
        return $this->outlet->getReportPaginated($request,$per_page, $this->company_id);
    }

//    public function postExportFile(Request $request)
//    {
//        $export_type    = $request['export_type'];
//        $format_arr = ['xls','xlsx','csv','pdf'];
//        if (! in_array($export_type, $format_arr)) {
//            $export_type = 'pdf';
//        }
//        $file_name = 'Export-agent-' . date("d-m-Y");
//        $start_date = $request['start_date'];
//        $end_date = $request['end_date'];
//        if ($start_date != '' && $end_date != '') {
//            $file_name = 'Export-agent-from-' . $start_date . '-To-' . $end_date;
//        }
//
//       // $data = [ 'Nmae' => "Mamun"];
//        $data = $this->agent->exportFile($request);
//
//        if (empty($data)) {
//            $this->response['success'] = false;
//            $this->response['msg']  = "Didn't found any data !";
//            return response($this->response,200);
//        }
//
//        return Excel::create($file_name, function ($excel) use ($data) {
//            $excel->sheet('mySheet', function ($sheet) use ($data) {
//                $sheet->fromArray($data);
//            });
//        })->store($export_type, 'exports/', true);
//    }

    public function create()
    {
        return view('agent.outlet-category.create',compact('companies'));
    }

    public function store(OutletCategoryRequest $request)
    {
        $outlet = $this->outlet->store($request, $this->agent_id);
        if ($outlet > 0) {
            return redirect('outlet-category')->with('flashMessageSuccess','The outlet has successfully created !');
        }
        return redirect('outlet-category')->with('flashMessageError','Unable to create outlet');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$agent_id)
    {
        $this->checkAgent($agent_id);
        $formData = OutletCategories::find($id);
        if (empty($formData)){
            return redirect('outlet-category')->with('flashMessageError','Invalid id.');
        }
        return view('agent.outlet-category.edit',compact('formData','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OutletCategoryRequest $request)
    {
            $agent = $this->outlet->update($request);
            if ($agent) {
                return redirect('outlet-category')->with('flashMessageSuccess','The outlet category successfully updated.');
            }
        return redirect('outlet-category')->with('flashMessageError','Unable to updated outlet category');
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
        $this->outlet->delete($id);
        return redirect('outlet-category')->with('flashMessageSuccess','The outlet category successfully deleted.');
    }
    public function checkAgent($agent_id){
        if ($agent_id != getLogedinAgentId()){
            return redirect('outlet-category')->with('flashMessageError','Invalid action');
        }
        return true;
    }
    
//    public function getAgentDetails(Request $request)
//    {
//        $agent_id    = $request['agent_id'];
//        $member_id      = $request['member_id'];
//
//        $this->response['agent'] = $this->getUserDetails($member_id);
//        $this->response['vehicle']  = $this->getAgentVehicleInfo($agent_id);
//        $this->response['driver']   = $this->getAgentDriverInfo($agent_id);
//
//        return response($this->response,200);
//    }
//
//    public function changePassword()
//    {
//        // return view('admin.admin-user.change-password');
//        return view('agent.agent.change-password');
//    }
//
//    public function postChangePassword(Request $inputs)
//    {
//
//        $validator = Validator::make($inputs->all(), [
//            'current_password'      => "required|min:6",
//            'new_password'          => 'required|min:6|max:60|different:current_password',
//            'confirm_new_password'  => 'required|min:6|max:60|same:new_password'
//        ]);
//
//        if ($validator->fails()) {
//            return redirect('change-password')
//                ->withErrors($validator)
//                ->withInput();
//        }
//
//        $info = DB::table('members')
//            ->select('password')
//            ->where('id', get_logged_user_id())
//            ->first();
//
//        if (empty($info)) {
//            $validator->getMessageBag()->add('users', 'Invalid user !');
//
//            return redirect('change-password')
//                ->withErrors($validator)
//                ->withInput();
//        }
//
//        if (! Hash::check($inputs->all()['current_password'], $info->password)) {
//            $validator->getMessageBag()->add('current_password', 'Does not math current password!');
//
//            return redirect('change-password')
//                ->withErrors($validator)
//                ->withInput();
//        }
//
//        DB::table('members')
//            ->where('id', get_logged_user_id())
//            ->update(['password' => bcrypt($inputs->all()['new_password'])]);
//
//        return redirect('user-profile')->with('flashMessageSuccess','The user successfully updated.');
//    }
//
//    public function getViewProfile()
//    {
//        $auth_info = DB::table('members as a')
//            ->select('a.*','b.*', 'r.role_name')
//            ->join('agents as b', 'b.member_id', '=', 'a.id')
//            ->join('role_member as c', 'c.member_id', '=', 'a.id')
//            ->join('roles as r', 'r.id', '=', 'c.role_id')
//            ->where('a.id', get_logged_user_id())
//            ->first();
//
//        return view('agent.agent.profile')
//            ->withUser($auth_info);
//    }
//
//    public function getEditProfile()
//    {
//        $auth_info = DB::table('members as a')
//            ->select('a.*','b.*', 'r.role_name')
//            ->join('agents as b', 'b.member_id', '=', 'a.id')
//            ->join('role_member as c', 'c.member_id', '=', 'a.id')
//            ->join('roles as r', 'r.id', '=', 'c.role_id')
//            ->where('a.id', get_logged_user_id())
//            ->first();
//
//        return view('agent.agent.edit_profile')
//            ->withUser($auth_info);
//    }
//
//    public function postEditProfile(EditProfile $request)
//    {
//        $id = $this->agent->updateProfile($request, get_logged_user_id());
//        if ($id > 0) {
//            return redirect('user-profile')->with('flashMessageSuccess','Profile successfully updated.');
//        }
//
//        return redirect('user-profile')->with('flashMessageError','Unable to updated profile');
//    }
//
//    public function getCompanyProfile()
//    {
//        $company_info = DB::table('companies as com')
//            ->select(
//                'com.id as new_id',
//                'com.*',
//                DB::raw("(SELECT
//                    GROUP_CONCAT(CONCAT(ag.first_name, '  ', ag.last_name) SEPARATOR ', ') AS agent
//                FROM
//                    agents AS ag
//                        INNER JOIN
//                    agent_company AS ac ON ac.agent_id = ag.id
//                WHERE
//                    ac.company_id = new_id) AS agent")
//            )->join('agent_company as agco', 'agco.company_id', '=', 'com.id')
//            ->join('agents as agent', 'agent.id', '=', 'agco.agent_id')
//            ->join('members as m', 'm.id', '=', 'agent.member_id')
//            ->where('com.status', '!=',  2)
//            ->where(['m.id' => get_logged_user_id() ])
//            ->first();
//
//        if (empty($company_info)) {
//            return redirect()->back()->with('flashMessageWarning','There is no company info found !');
//        }
//
//        return view('agent.agent.company-info')
//            ->withInfo($company_info);
//    }
}
