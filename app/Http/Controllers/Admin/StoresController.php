<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\Merchant;
use App\DB\Admin\Stores;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Stores\StoresRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\StoresRequest;
use Excel;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class StoresController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $stores;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * Stores Controller constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        StoresRepository $stores
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->stores = $stores;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
    public function index(Request $request)
    {
        return view('admin.stores.index');
    }

    public function getDataTableReport(Request $request){
        return $this->stores->getReportPaginated($request);
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
        $data = $this->stores->exportFile($request);

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
    
    public function getZoneDetails(Request $request)
    {
        $stores_id    = $request['Zone_id'];
        $member_id      = $request['member_id'];
        
        $this->response['Zone'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getZoneVehicleInfo($stores_id);
        $this->response['driver']   = $this->getZoneDriverInfo($stores_id);
        
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
            echo"<option value=''>..No Sub Stores found ..</option>";
        }
    }
}
