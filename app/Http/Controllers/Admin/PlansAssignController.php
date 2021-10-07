<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\Merchant;
use App\DB\Admin\Plans;
use App\DB\Admin\PlansAssign;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\PlansAssign\PlansAssignRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\PlanAssignRequest;
use App\Http\Requests\Admin\PlanEditRequest;
use Excel;
use DB;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class PlansAssignController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $plansassign;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * PlanController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        PlansAssignRepository $plansassign
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->plans_assign = $plansassign;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
    public function index(Request $request)
    {
        return view('admin.plans-assign.index');
    }

    public function postExportFile(Request $request)
    {
        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-Plan-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-Plan-from-' . $start_date . '-To-' . $end_date;
        }

       // $data = [ 'Nmae' => "Mamun"];
        $data = $this->plans_assign->exportFile($request);

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
        return $this->plans_assign->getReportPaginated($request);
    }

    public function create()
    {
        $merchants = Merchant::select('first_name','last_name','id')->where('status',1)->get();
        $plans = Plans::select('plan_name','id')->where('status',1)->get();
        return view('admin.plans-assign.create',compact('merchants','plans'));
    }

    public function store(PlanAssignRequest $request)
    {
        $plansassign_id = $this->plans_assign->store($request);
        if ($plansassign_id > 0) {
            return redirect('admin/plan-assign')->with('flashMessageSuccess','The Plan has successfully created !');
        }
        return redirect('admin/plan-assign')->with('flashMessageError','Unable to create Plan');
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
        $merchants = DB::table('merchants as merch')
            ->select('m.email as m_email','m.mobile_no as m_mobile','merch.first_name','merch.last_name','merch.id','merch.profile_pic')
            ->where('merch.id',$id)
            ->join('members as m', 'merch.member_id', '=', 'm.id')
            ->first();
        $deliveredPlans = $this->plans_assign->getPlannedByType('DELIVERED');
        $deliveredPlansAssign = $this->plans_assign->findOrThrowException($id, 'DELIVERED');

        $returnedPlans = $this->plans_assign->getPlannedByType('RETURNED');
        $returnedPlansAssign = $this->plans_assign->findOrThrowException($id, 'RETURNED');
        return view('admin.plans-assign.edit',compact('deliveredPlansAssign','returnedPlansAssign','merchants','deliveredPlans','returnedPlans','id'))
            ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlanAssignRequest $request, $member_id)
    {
        $role_id = 3;
        /*if ($request->get('has_company') == 1) {
            $role_id = 11;
        }*/

        $plansassign = $this->plans_assign->update($request, $member_id);
        if ($plansassign) {
            return redirect('admin/merchant')->with('flashMessageSuccess','The Plan successfully updated.');
        }
        return redirect('admin/merchant')->with('flashMessageError','Unable to updated Plan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->plans_assign->delete($id);
        return redirect('admin/plan-assign')->with('flashMessageSuccess','The Plan successfully deleted.');
    }
    
    public function viewPlans(Request $request)
    {
        $plans = $this->plans_assign->viewPlan($request);
        if (!empty($plans)){
            return response()->json(['success'=>true,'result'=>$plans]);
        }
        return response()->json(['success'=>false,'result'=>'','msg'=>'Plan not found.']);
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
}
