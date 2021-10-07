<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\Merchant;
use App\DB\Admin\Plans;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Plans\PlansRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\PlanRequest;
use App\Http\Requests\Admin\PlanEditRequest;
use Excel;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class PlansController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $plan;

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
        PlansRepository $plan
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->plans = $plan;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
    public function index(Request $request)
    {
        return view('admin.plan.index');
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
        $data = $this->plans->exportFile($request);

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
        return $this->plans->getReportPaginated($request);
    }

    public function create()
    {
        $merchants = Merchant::select('first_name','last_name','id')->get();
        return view('admin.plan.create',compact('merchants'));
    }

    public function store(PlanRequest $request)
    {
        $plan_id = $this->plans->store($request);
        if ($plan_id > 0) {
            return redirect('admin/plan')->with('flashMessageSuccess','The Plan has successfully created !');
        }
        return redirect('admin/plan')->with('flashMessageError','Unable to create Plan');
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
        $merchants = Merchant::select('first_name','last_name','id')->get();
        $plan = $this->plans->findOrThrowException($id);
        return view('admin.plan.edit')
            ->withPlan($plan)
            ->withmerchants($merchants)
            ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlanRequest $request, $member_id)
    {
        $plan = $this->plans->update($request, $member_id);
        if ($plan) {
            return redirect('admin/plan')->with('flashMessageSuccess','The Plan successfully updated.');
        }
        return redirect('admin/plan')->with('flashMessageError','Unable to updated Plan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->plans->delete($id);
        return redirect('admin/plan')->with('flashMessageSuccess','The Plan successfully deleted.');
    }
    
    public function getPlanDetails(Request $request)
    {
        $plan_id    = $request['Plan_id'];
        $member_id      = $request['member_id'];
        
        $this->response['Plan'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getPlanVehicleInfo($plan_id);
        $this->response['driver']   = $this->getPlanDriverInfo($plan_id);
        
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
}
