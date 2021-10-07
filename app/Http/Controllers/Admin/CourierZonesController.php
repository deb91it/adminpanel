<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\Merchant;
use App\DB\Admin\CourierZones;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\CourierZones\CourierZonesRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\CourierZonesRequest;
use App\Http\Requests\Admin\ZoneEditRequest;
use Excel;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class CourierZonesController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $courier_zones;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * ZoneController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        CourierZonesRepository $courier_zones
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->courier_zones = $courier_zones;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
    public function index(Request $request)
    {
        return view('admin.courier_zones.index');
    }

    public function postExportFile(Request $request)
    {
        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-Zone-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-Zone-from-' . $start_date . '-To-' . $end_date;
        }

       // $data = [ 'Nmae' => "Mamun"];
        $data = $this->courier_zones->exportFile($request);

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
        return $this->courier_zones->getReportPaginated($request);
    }

    public function create()
    {
        $merchants = Merchant::select('first_name','last_name','id')->get();
        return view('admin.courier_zones.create',compact('merchants'));
    }

    public function store(CourierZonesRequest $request)
    {
        $courier_zones_id = $this->courier_zones->store($request);
        if ($courier_zones_id > 0) {
            return redirect('admin/courier-zone')->with('flashMessageSuccess','The Zone has successfully created !');
        }
        return redirect('admin/courier-zone')->with('flashMessageError','Unable to create Zone');
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
        $courier_zones = $this->courier_zones->findOrThrowException($id);
        return view('admin.courier_zones.edit',compact('courier_zones'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourierZonesRequest $request, $member_id)
    {
        $courier_zones = $this->courier_zones->update($request, $member_id);
        if ($courier_zones) {
            return redirect('admin/courier-zone')->with('flashMessageSuccess','The Zone successfully updated.');
        }
        return redirect('admin/courier-zone')->with('flashMessageError','Unable to updated Zone');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->courier_zones->delete($id);
        return redirect('admin/courier-zone')->with('flashMessageSuccess','The Zone successfully deleted.');
    }

    public function getZoneDetails(Request $request)
    {
        $courier_zones_id    = $request['Zone_id'];
        $member_id      = $request['member_id'];

        $this->response['Zone'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getZoneVehicleInfo($courier_zones_id);
        $this->response['driver']   = $this->getZoneDriverInfo($courier_zones_id);
        
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
