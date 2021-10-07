<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\CourierZones;
use App\DB\Admin\Delivery;
use App\DB\Admin\TrackingDetailsSummary;
use Dropbox\Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Delivery\DeliveryRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Repositories\Admin\FCMNotification\FCMNotificationRepository;
use App\Http\Requests\Admin\DeliveryRequest;
use App\Http\Requests\Admin\StoreRiderRequest;
use Excel;
use DB;
use Illuminate\Support\Facades\Hash;
use PDF;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class DeliveryController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $delivery;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;
    protected $notification;

    /**
     * Delivery Controller constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        DeliveryRepository $delivery
        , RoleRepository $roles
        , MemberRepository $member
        , FCMNotificationRepository $notification
        , AddressRepository $address)
    {
        $this->delivery = $delivery;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
        $this->notification = $notification;
    }
    public function index(Request $request)
    {
//        echo Hash::make("dev!admin@2021!001");exit();
        $obj = new \stdClass();
        $obj->is_hub = 2;
        $allStatus = $this->delivery->allStatus();
        $getStatus = $this->delivery->getStatus();
        $getHubs = $this->delivery->getHubs();
        $getZones = $this->delivery->getZones();
        $getUserList = $this->delivery->getUserList();
        $getRiders = $this->delivery->getRiders($obj);
        return view('admin.delivery.index',compact('getStatus','allStatus','getHubs','getZones','getRiders','getUserList'));
    }

    public function amendmentDelivery(Request $request)
    {
        $obj = new \stdClass();
        $obj->is_hub = 2;
        $allStatus = $this->delivery->allStatus();
//        $allStatus = $this->delivery->amendmentStatus();
        $getStatus = $this->delivery->getStatus();
        $getHubs = $this->delivery->getHubs();
        $getZones = $this->delivery->getZones();
        $getRiders = $this->delivery->getRiders($obj);
        return view('admin.amendment-delivery.index',compact('getStatus','allStatus','getHubs','getZones','getRiders'));
    }

    public function getDataTableReport(Request $request){
        return $this->delivery->getReportPaginated($request);
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
            $file_name = 'Delivery-Export-Zone-from-' . $start_date . '-To-' . $end_date;
        }

       // $data = [ 'Nmae' => "Mamun"];
        $data = $this->delivery->exportFile($request);

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
        $plans = $this->delivery->getProducts($request);
        if (!empty($plans)){
            return response()->json(['success'=>true,'result'=>$plans,'delivery_id'=>$request->delivery_id]);
        }
        return response()->json(['success'=>false,'result'=>'','msg'=>'Product not found.']);
    }

    public function productsApproval(Request $request)
    {
        $approval = $this->delivery->setApproval($request);
        if (!empty($approval)){
            return response()->json(['success'=>true,'result'=>$approval,'msg'=>'Products successfully approved.']);
        }
        return response()->json(['success'=>false,'result'=>'','msg'=>'Product not found.']);
    }

    public function getRiders(Request $request)
    {
        $approval = $this->delivery->getRiders($request);
        $name = $request->is_hub == 1 ? 'Hub Manager' : 'Rider';
        if ( !empty($approval)) {
            echo"<option value=''>...Select...</option>";
            foreach($approval as $app)
            {
                echo "<option value='$app->id'> $app->name  (<b>".$name.": ".$app->main_name."</b>)</option>";
            }
        } else {
            echo"<option value=''>..No Rider found ..</option>";
        }
    }
    
    public function getZoneDetails(Request $request)
    {
        $delivery_id    = $request['Zone_id'];
        $member_id      = $request['member_id'];
        $this->response['Zone'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getZoneVehicleInfo($delivery_id);
        $this->response['driver']   = $this->getZoneDriverInfo($delivery_id);
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
            echo"<option value=''>..No Sub Delivery found ..</option>";
        }
    }

    public function create(){
        $hubs = DB::table("hub")->where("status", 1)->get();
        return view('admin.delivery.create',compact("hubs"))
            ->withZone(DB::table('courier_zones')->lists('zone_name', 'id'))
            ->withMerchant (DB::table('merchants')->select(DB::raw('CONCAT("( ",merchants.business_name, " ) ",merchants.first_name, " ", merchants.last_name ) AS full_name'), 'merchants.id')->lists('full_name', 'id'))
            ->withPlan(DB::table('plans')->lists('plan_name', 'id'))
            ->withHub(DB::table('hub')->lists('hub_name', 'id'));
    }

    public function store(DeliveryRequest $requests){
        $delivery_id = $this->delivery->store($requests);
        if ($delivery_id > 0) {
            if ($requests['save'] == 'form_submit_n_print') {
                $customVal = array($delivery_id);
                $store_id = json_encode($customVal);
                return \Redirect::route('admin.delivery.invoice.pdf',($store_id));
            }
            return redirect('admin/delivery')->with('flashMessageSuccess','The delivery has successfully created !');
        }
        return redirect('admin/delivery')->with('flashMessageError','Unable to create delivery');
    }

    public function storeRiders(StoreRiderRequest $requests){

        try {
            if (empty($requests->deliveried_id))
            {
                return redirect('admin/delivery')->with('flashMessageError','Please select at least one item from the list.');
            }
            if ($requests['flag_status_id'] <= 11)
            {
                $duplicateExists = $this->delivery->checkDuplicateEntry($requests);
                if ($duplicateExists > 0)
                {
                    return redirect('admin/delivery')->with('flashArrayMessageError',$duplicateExists);
                }
                if ($requests['flag_status_id'] == 6 || $requests['flag_status_id'] == 7 || $requests['flag_status_id'] == 8)
                {
                    $checkTransit = $this->delivery->checkTransitBeforeDelivered($requests);
                    if (empty($checkTransit))
                    {
                        return redirect('admin/delivery')->with('flashArrayMessageError',$checkTransit);
                    }
                }
            }
            $store_id = $this->delivery->storeRiders($requests);
            if (is_array($store_id)) {
                $store_id = json_encode($store_id);
                return \Redirect::route('admin.delivery.invoice.pdf',($store_id));
            }
            if ($store_id > 0) {
                return redirect('admin/delivery')->with('flashMessageSuccess','The delivery has successfully sorted !');
            }
            return redirect('admin/delivery')->with('flashMessageError','Unable to sorted delivery');
        }catch (Exception $exception)
        {
            return redirect('admin/delivery')->with('flashMessageSuccess',$exception->getMessage());
        }
    }

    public function storeRidersAmendment(StoreRiderRequest $requests){
        if (empty($requests->deliveried_id))
        {
            return redirect('admin/amendment-delivery')->with('flashMessageError','Please select at least one item from the list.');
        }
        $paidStatus = $this->delivery->checkAmendmentDeliveryPaidStatus($requests);
        if (!empty($paidStatus))
        {
            return redirect('admin/amendment-delivery')->with('flashArrayMessageError', $paidStatus);
        }
        $store_id = $this->delivery->storeRiders($requests);
        $rollback = $this->delivery->rollBackStatus($requests);

        if (is_array($store_id)) {
            $store_id = json_encode($store_id);
            return \Redirect::route('admin.delivery.invoice.pdf',($store_id));
        }
        if ($store_id > 0) {
            return redirect('admin/amendment-delivery')->with('flashMessageSuccess','The delivery has successfully sorted !');
        }
        return redirect('admin/amendment-delivery')->with('flashMessageError','Unable to sorted delivery');
    }



    public function paymentReceivedStoreRiders(Request $requests)
    {
        if (empty($requests->deliveried_id))
        {
            return redirect('admin/delivery')->with('flashMessageError','Please select at least one item from the list.');
        }
        $duplicateExists = $this->delivery->checkDuplicateEntry($requests);
        if ($duplicateExists > 0)
        {
            return redirect('admin/delivery')->with('flashArrayMessageError',$duplicateExists);
        }
        $checkTransit = $this->delivery->checkTransitBeforeDelivered($requests);
        if (!empty($checkTransit))
        {
            return redirect('admin/delivery')->with('flashArrayMessageError',$checkTransit);
        }
        $store_id = $this->delivery->storeRiders($requests);
        if (is_array($store_id)) {
            $store_id = json_encode($store_id);
            return \Redirect::route('admin.delivery.invoice.pdf',($store_id));
        }
        if ($store_id > 0) {
            return redirect('admin/delivery')->with('flashMessageSuccess','The delivery has successfully sorted !');
        }
        return redirect('admin/delivery')->with('flashMessageError','Unable to sorted delivery');
    }

    public function paymentReceivedStoreRidersAmendment(Request $requests)
    {
//        dd($requests->all());
        if (empty($requests->deliveried_id))
        {
            return redirect('admin/amendment-delivery')->with('flashMessageError','Please select at least one item from the list.');
        }
//        $DuplicateExists = $this->delivery->checkDuplicateEntry($requests);
//        if ($DuplicateExists > 0)
//        {
//            $flagMesage = $this->showFlagMessage($requests);
//            return redirect('admin/delivery')->with('flashMessageError', $flagMesage);
//        }
        $rollback = $this->delivery->rollBackStatus($requests);
        $store_id = $this->delivery->storeRiders($requests);
        if (is_array($store_id)) {
            $store_id = json_encode($store_id);
            return \Redirect::route('admin.delivery.invoice.pdf',($store_id));
        }
        if ($store_id > 0) {
            return redirect('admin/amendment-delivery')->with('flashMessageSuccess','Consignment has successfully rolled back !');
        }
        return redirect('admin/amendment-delivery')->with('flashMessageError','Unable to sorted delivery');
    }

    public function edit($id){
        $hubs = DB::table("hub")->where("status", 1)->get();
        $tracker = $this->delivery->trackingDetails($id);
        $getStatus = $this->delivery->getStatus();
        $allStatus = $this->delivery->allStatus();
        $delivery = $this->delivery->findOrThrowException($id);
        $deliveredPlan = $this->delivery->getAllPlansByMerchantId($delivery->merchant_id,'DELIVERED');
        $returnedPlan = $this->delivery->getAllPlansByMerchantId($delivery->merchant_id,'RETURNED');
        return view('admin.delivery.edit',compact('tracker','getStatus','allStatus','deliveredPlan','returnedPlan','hubs'))
            ->withDelivery($delivery)
            ->withZone(DB::table('courier_zones')->lists('zone_name', 'id'))
            ->withMerchant (DB::table('merchants')->select(DB::raw('CONCAT("( ",merchants.business_name, " ) ",merchants.first_name, " ", merchants.last_name ) AS full_name'), 'merchants.id')->lists('full_name', 'id'))
            ->withStore(DB::table('stores')->where('merchant_id', $delivery->merchant_id)->lists('name', 'id'))
            ->withProducts(DB::table('products')->where('merchant_id', $delivery->merchant_id)->lists('name', 'id'))
            //->withPlan(DB::table('plan_assign_to_merchant')->where('')->lists('plan_name', 'id'))
            ->withHub(DB::table('hub')->lists('hub_name', 'id'));

    }

    public function update(DeliveryRequest $requests, $id ){
        $delivery_id = $this->delivery->update($requests, $id);
        if ($delivery_id > 0) {
            return redirect('admin/delivery')->with('flashMessageSuccess','The delivery has successfully updated !');
        }
        return redirect('admin/delivery')->with('flashMessageError','Unable to update delivery');
    }

    public function delete( $id ){
        $delivery_id = $this->delivery->destroy($id);
        if ($delivery_id > 0) {
            return redirect('admin/delivery')->with('flashMessageSuccess','The delivery has successfully deleted !');
        }
        return redirect('admin/delivery')->with('flashMessageError','Unable to delete delivery');
    }

    public function getProductByMerchantId(Request $request)
    {
        $products = $this->delivery->getAllProductByMerchantId($request['merchant_id']);
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
        $stores = $this->delivery->getAllStoreByMerchantId($request['merchant_id']);
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

    public function getPlansByMerchantId(Request $request)
    {
        $plans = $this->delivery->getAllPlansByMerchantId($request['merchant_id'],"DELIVERED");
        if ( !empty($plans)) {
            echo"<option value=''>...Select plan...</option>";
            foreach($plans as $plan)
            {
                $text = $plan->plan_name.' - code: '.$plan->plan_code;
                echo "<option value='$plan->plan_id'> $text </option>";
            }
        } else {
            echo"<option value=''>..No plan found ..</option>";
        }
    }

    public function getReturnPlansByMerchantId(Request $request)
    {
        $plans = $this->delivery->getAllPlansByMerchantId($request['merchant_id'],"RETURNED");
        if ( !empty($plans)) {
            echo"<option value=''>...Select plan...</option>";
            foreach($plans as $plan)
            {
                $text = $plan->plan_name.' - code: '.$plan->plan_code;
                echo "<option value='$plan->plan_id'> $text </option>";
            }
        } else {
            echo"<option value=''>..No plan found ..</option>";
        }
    }



    /*public function deliveryDetailsPdf(){
        $pdf = PDF:: loadView('admin.delivery.delivery_invoice_pdf');
        return $pdf -> download('delivery_invoice.pdf');
    }*/

    public function generatePdf($dataArray){
        $invoices = json_decode($dataArray);
        return view('admin.delivery.partial.delivery_invoice_pdf',compact('invoices'));
    }

    public function deliveryInvoiceDetailsPdf($dataArray){
        $invoices = json_decode($dataArray);
        $pdf = PDF:: loadView('admin.delivery.partial.download_invoice_pdf',compact('invoices'))->setPaper('a4', 'portrait');
        $pdf->stream();
        return $pdf -> download('invoice.pdf');
    }

    public function consignmentTrack($consignment_id = null){
        $delivery = [];
        $trackingSummary = [];
        $msg = 'Missing consignment id !';
        if ($consignment_id) {
            $delivery = Delivery::where('consignment_id',$consignment_id)->first();
            if (!empty($delivery)) {
                $msg = '';
                $trackingSummary = TrackingDetailsSummary::where('deliveries_id', $delivery->id)->first();
            } else {
                $msg = 'Did not found data by your providing consignment id !';
            }
        }
        return view('admin.delivery.partial.tracking_summary', compact('consignment_id','trackingSummary', 'delivery', 'msg'));
    }

    public function paymentReceived(Request $request){
        $result = $this->delivery->getAllDeliveries($request);
        return response()->json(['success'=>true, 'response' => $result]);
    }

    public function checkUserByNumber(Request $request) {
        $is_exist = $this->delivery->checkUserByNumber($request);
        if (empty($is_exist))
        {
            return response()->json(["success" => false, "resp" => '']);
        }
        return response()->json(["success" => true, "resp" => $is_exist, "msg" => "Data added successfully."]);
    }

}
