<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\CourierZones;
use App\DB\Admin\Hub;
use App\DB\Agent;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Agent\AgentRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\AgentRequest;
use App\Http\Requests\Admin\AgentEditRequest;
use Excel;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class AgentController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $agent;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * AgentController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        AgentRepository $agent
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->agent = $agent;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
    public function index(Request $request)
    {
        return view('admin.agent.index');
    }

    public function postExportFile(Request $request)
    {
        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-agent-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-agent-from-' . $start_date . '-To-' . $end_date;
        }

       // $data = [ 'Nmae' => "Mamun"];
        $data = $this->agent->exportFile($request);

        if (empty($data)) {
            $this->response['success'] = false;
            $this->response['msg']  = "Didn't found any data !";
            return response($this->response,200);
        }

        return Excel::create($file_name, function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->store($export_type, 'exports/', true);
    }

    public function getDataTableReport(Request $request){
        return $this->agent->getReportPaginated($request);
    }

    public function create()
    {
        $hubs = Hub::pluck('hub_name','id')->toArray();
        $cZones = CourierZones::pluck('zone_name','id')->toArray();
        return view('admin.agent.create',compact('hubs','cZones'))
            ->withRoles($this->roles->getLists())
            ->withLanguages($this->address->getLanguageList())
            ->withCountries($this->address->getAllCountries());
    }

    public function store(AgentRequest $request)
    {
        $role_id = 2;

       /* if ($request->get('has_company') == 1) {
            $role_id = 11;
        }*/

        $member_id = $this->member->create($request, $user_type = 1, $model_id = 2, $role_id);
        if ($member_id > 0) {
            $agent_id = $this->agent->store($request, $member_id);
            if ($agent_id > 0) {
                return redirect('admin/agent')->with('flashMessageSuccess','The agent has successfully created !');
            }
        }
        return redirect('admin/agent')->with('flashMessageError','Unable to create agent');
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
    public function edit($id)
    {
        $hubs = Hub::pluck('hub_name','id')->toArray();
        $cZones = CourierZones::pluck('zone_name','id')->toArray();
        $agent = $this->agent->findOrThrowException($id);
        return view('admin.agent.edit',compact('hubs','cZones'))
            ->withAgent($agent)
            ->withRoles($this->roles->getLists());

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AgentEditRequest $request, $member_id)
    {
        $role_id = 2;
        /*if ($request->get('has_company') == 1) {
            $role_id = 11;
        }*/

        $member = $this->member->update($request, $member_id, $role_id);
        if ($member) {
            $agent = $this->agent->update($request, $member_id);
            if ($agent) {
                return redirect('admin/agent')->with('flashMessageSuccess','The agent successfully updated.');
            }
        }
        return redirect('admin/agent')->with('flashMessageError','Unable to updated agent');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->agent->delete($id);
        return redirect('admin/agent')->with('flashMessageSuccess','The agent successfully deleted.');
    }
    
    public function getAgentDetails(Request $request)
    {
        $agent_id    = $request['agent_id'];
        $member_id      = $request['member_id'];
        
        $this->response['agent'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getAgentVehicleInfo($agent_id);
        $this->response['driver']   = $this->getAgentDriverInfo($agent_id);
        
        return response($this->response,200);
    }

    public function getZoneByCountryId(Request $request)
    {
        $cities = $this->address->getZoneByCountryId($request['country_id']);
        if ( !empty($cities)) {
            echo"<option value=''>...Select City...</option>";
            foreach($cities as $city)
            {
                echo "<option value='$city->zone_id'> $city->name </option>";
            }
        } else {
            echo"<option value=''>..No Sub Zone found ..</option>";
        }
    }

    public function getPickUpListsByRider(Request $request,$id)
    {
        $rider = $this->agent->findOrThrowException($id);
        if (empty($rider))
        {
            return redirect('admin/agent')->with('flashMessageError','Invalid Rider Id');
        }
        return view('admin.agent.rider_pickup_list',compact('rider'));
    }

    public function getDataTablePickUpLists(Request $request){
        return $this->agent->getPickupList($request);
    }

    public function getDeliveryListsByRider(Request $request,$id)
    {
        $rider = $this->agent->findOrThrowException($id);
        if (empty($rider))
        {
            return redirect('admin/agent')->with('flashMessageError','Invalid Rider Id');
        }
        return view('admin.agent.rider_delivery_list',compact('rider'));
    }

    public function getDataTableDeliveryLists(Request $request){
        return $this->agent->getDeliveryLists($request);
    }
}
