<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\CourierZones;
use App\DB\Admin\Delivery;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Collected\CollectedRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\CollectedRequest;
use App\Http\Requests\Admin\StoreRiderRequest;
use Excel;
use DB;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class CollectedController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $collected;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * Collected Controller constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        CollectedRepository $collected
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->collected = $collected;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
    public function index(Request $request)
    {
        $getStatus = $this->collected->getStatus();
        return view('admin.collected.index',compact('getStatus'));
    }

    public function getDataTableReport(Request $request){
        return $this->collected->getReportPaginated($request);
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
        $data = $this->collected->exportFile($request);

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

    public function viewProducts(Request $request)
    {
        $plans = $this->collected->getProducts($request);
        if (!empty($plans)){
            return response()->json(['success'=>true,'result'=>$plans,'Collected_id'=>$request->Collected_id]);
        }
        return response()->json(['success'=>false,'result'=>'','msg'=>'Product not found.']);
    }

    public function productsApproval(Request $request)
    {
        $approval = $this->collected->setApproval($request);
        if (!empty($approval)){
            return response()->json(['success'=>true,'result'=>$approval,'msg'=>'Products successfully approved.']);
        }
        return response()->json(['success'=>false,'result'=>'','msg'=>'Product not found.']);
    }

    public function getRiders(Request $request)
    {
        $approval = $this->collected->getRiders($request);
        if ( !empty($approval)) {
            echo"<option value=''>...Select store...</option>";
            foreach($approval as $app)
            {
                echo "<option value='$app->id'> $app->name </option>";
            }
        } else {
            echo"<option value=''>..No Rider found ..</option>";
        }
    }
    
    public function getZoneDetails(Request $request)
    {
        $collected_id    = $request['Zone_id'];
        $member_id      = $request['member_id'];
        $this->response['Zone'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getZoneVehicleInfo($collected_id);
        $this->response['driver']   = $this->getZoneDriverInfo($collected_id);
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
            echo"<option value=''>..No Sub Collected found ..</option>";
        }
    }

    public function create(){
        return view('admin.Collected.create')
            ->withZone(DB::table('courier_zones')->lists('zone_name', 'id'))
            ->withMerchant (DB::table('merchants')->select(DB::raw('CONCAT(merchants.first_name, " ", merchants.last_name ) AS full_name'), 'merchants.id')->lists('full_name', 'id'))
            ->withPlan(DB::table('plans')->lists('plan_name', 'id'));
    }

    public function store(CollectedRequest $requests){
        $collected_id = $this->collected->store($requests);
        if ($collected_id > 0) {
            return redirect('admin/Collected')->with('flashMessageSuccess','The Collected has successfully created !');
        }
        return redirect('admin/Collected')->with('flashMessageError','Unable to create Collected');
    }

    public function storeRiders(StoreRiderRequest $requests){
        $store_id = $this->collected->storeRiders($requests);
        if ($store_id > 0) {
            return redirect('admin/collected')->with('flashMessageSuccess','The Collected has successfully sorted !');
        }
        return redirect('admin/collected')->with('flashMessageError','Unable to sorted Collected');
    }

    public function edit($id){
        $tracker = $this->collected->trackingDetails($id);
        $getStatus = $this->collected->getStatus();
        $collected = $this->collected->findOrThrowException($id);
        return view('admin.Collected.edit',compact('tracker','getStatus'))
            ->withCollected($collected)
            ->withZone(DB::table('courier_zones')->lists('zone_name', 'id'))
            ->withMerchant (DB::table('merchants')->select(DB::raw('CONCAT(merchants.first_name, " ", merchants.last_name ) AS full_name'), 'merchants.id')->lists('full_name', 'id'))
            ->withStore(DB::table('stores')->where('merchant_id', $collected->merchant_id)->lists('name', 'id'))
            ->withProducts(DB::table('products')->where('merchant_id', $collected->merchant_id)->lists('name', 'id'))
            ->withPlan(DB::table('plans')->lists('plan_name', 'id'));

    }

    public function update(CollectedRequest $requests, $id ){
        $collected_id = $this->collected->update($requests, $id);
        if ($collected_id > 0) {
            return redirect('admin/Collected')->with('flashMessageSuccess','The Collected has successfully updated !');
        }
        return redirect('admin/Collected')->with('flashMessageError','Unable to update Collected');
    }

    public function getProductByMerchantId(Request $request)
    {
        $products = $this->collected->getAllProductByMerchantId($request['merchant_id']);
        if ( !empty($products)) {
            echo"<option value=''>...Select product...</option>";
            foreach($products as $product)
            {
                echo "<option value='$product->id'> $product->name </option>";
            }
        } else {
            echo"<option value=''>..No product found ..</option>";
        }
    }

    public function getStoreByMerchantId(Request $request)
    {
        $stores = $this->collected->getAllStoreByMerchantId($request['merchant_id']);
        if ( !empty($stores)) {
            echo"<option value=''>...Select store...</option>";
            foreach($stores as $store)
            {
                echo "<option value='$store->id'> $store->name </option>";
            }
        } else {
            echo"<option value=''>..No store found ..</option>";
        }
    }

}
