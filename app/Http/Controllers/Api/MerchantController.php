<?php

namespace App\Http\Controllers\Api;

use App\DB\Merchant;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Api\Merchant\MerchantRepository;
use App\Repositories\Api\Member\MemberRepository;
use App\Repositories\Api\Driver\DriverRepository;
use App\Repositories\Api\Vehicle\VehicleRepository;
use Illuminate\Support\Facades\Input;
use Validator;
use Excel;
use DB;
use Response;
use DateTime;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class MerchantController extends Controller
{

    /**
     * @var
     */
    protected $_errors;
    protected $success_code;
    protected $error_code;
    protected $invalid_msg;

    /**
     * @var DriverRepository
     */
    protected $merchant;
    protected $driver;


//    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $vehicle;



    /**
     * PassengerController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        VehicleRepository $vehicle
        , DriverRepository $driver
        , MemberRepository $member
        , MerchantRepository $merchant
    )

    {
        $this->driver = $driver;
        $this->member = $member;
        $this->vehicle = $vehicle;
        $this->merchant = $merchant;

        $this->success_code = 200;
        $this->error_code = 200;
        $this->invalid_msg = 'Your request is not valid';
    }

    private function getErrorAsString(){
        $errorString ="";
        foreach ($this->_errors as $error) {
            $errorString .= $error.",";
        }
        return $errorString;
    }

    /** DashBoard Territory Sales Executive */
    public function Dashboard(Request $request){
        $inputs = Input::json()->all();
//        return $inputs;
        if(!$this->validateDashboardRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = $this->error_code;
            $this->response['message']  = $this->invalid_msg;
            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
            return response($this->response, $this->error_code);
        }

        $is_results = $this->merchant->Dashboard($inputs);
        if($is_results){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['response']     = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);


    }

    protected function validateDashboardRequest($inputs)
    {
//        return $inputs;
        $validator = Validator::make($inputs, [
            'tse_id' => 'required|numeric',
            'company_id' => 'required|numeric',

        ],

            [
                'category_id.between' => 'In valid category id'
            ]);
        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function lineChartFilter(Request $request){
        $inputs = Input::json()->all();
//        return $inputs;
        if(!$this->validatelineChartFilterRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = $this->error_code;
            $this->response['message']  = $this->invalid_msg;
            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
            return response($this->response, $this->error_code);
        }

        $is_results = $this->merchant->lineChartFilter($inputs);
        if($is_results){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['response']     = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);


    }

    protected function validatelineChartFilterRequest($inputs)
    {
//        return $inputs;
        $validator = Validator::make($inputs, [
            'tse_id' => 'required|numeric',
            'company_id' => 'required|numeric',
            'fromDate' => 'required',
            'toDate' => 'required',
            'chartType' => 'required',

        ],

            [
                'category_id.between' => 'In valid category id'
            ]);
        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    public function listOfSR(Request $request){
        $inputs = Input::json()->all();
//        return $inputs;
        if(!$this->validatelistOfSRRequest($inputs)){
            $this->response['success']  = false;
            $this->response['code']     = $this->error_code;
            $this->response['message']  = $this->invalid_msg;
            $this->response['error']    = get_error_response($this->error_code,  $this->getErrorAsString());
            return response($this->response, $this->error_code);
        }

        $is_results = $this->merchant->listOfSR($inputs);
        if($is_results){
            $this->response['success']  = true;
            $this->response['code']     = $this->success_code;
            $this->response['response']     = $is_results;
            $this->response['message']  = "Result found successfully";
            return response($this->response, $this->success_code);
        }
        $this->response['success']  = false;
        $this->response['code']     = $this->error_code;
        $this->response['message']  = "Couldn't find any data";
        $this->response['error']    = get_error_response($this->error_code, "Couldn't find any data, please check your request format");
        return response($this->response, $this->error_code);


    }

    protected function validatelistOfSRRequest($inputs)
    {
//        return $inputs;
        $validator = Validator::make($inputs, [
            'tse_id' => 'required|numeric',
            'company_id' => 'required|numeric',

        ],

            [
                'category_id.between' => 'In valid category id'
            ]);
        if ($validator->fails()) {
            $this->_errors = $validator->errors()->all();
            return false;
        }
        return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = 30;
        return view('admin.merchant.index')
        ->withZones(DB::table('zones')->select('zone_id as id', 'name')->orderBy('zone_id', 'asc')->get())
        ->withAgents($this->agent->getAgentList())
        ->withCategories($this->vehicle->getCategoryList());

            //->withMerchants($this->merchant->getMerchantPaginated($request, $per_page));
    }

    public function postExportFile(Request $request)
    {
        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-merchant-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-merchant-from-' . $start_date . '-To-' . $end_date;
        }

       // $data = [ 'Nmae' => "Mamun"];
        $data = $this->merchant->exportFile($request);

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
        return $this->merchant->getReportPaginated($request);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.merchant.create')
            ->withRoles($this->roles->getLists())
            ->withLanguages($this->address->getLanguageList())
            ->withCountries($this->address->getAllCountries())
            ->withAgents($this->agent->getAgentList());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantRequest $request)
    {
        $member_id = $this->member->create($request, $user_type = 3, $model_id = 4, $role_id = 4);
        if ($member_id > 0) {
            $merchant_id = $this->merchant->store($request, $member_id);
            if ($merchant_id > 0) {
                return redirect('admin/merchant')->with('flashMessageSuccess','The merchant successfully added.');
            }
        }
        return redirect('admin/merchant')->with('flashMessageError','Unable to add merchant');
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
        $merchant = $this->merchant->findOrThrowException($id);
        return view('admin.merchant.edit')
            ->withMerchant($merchant)
            ->withRoles($this->roles->getLists())
            ->withLanguages($this->address->getLanguageList())
            ->withZone($this->address->getZoneListByCountryId($merchant->country_id))
            ->withCountries($this->address->getAllCountries())
            ->withAgents($this->agent->getAgentList());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantEditRequest $request, $member_id)
    {
        $member = $this->member->update($request, $member_id, $roles = null);
        if ($member) {
            $merchant = $this->merchant->update($request, $member_id);
            if ($merchant) {
                return redirect('admin/merchant')->with('flashMessageSuccess','The merchant successfully updated.');
            }
        }
        return redirect('admin/merchant')->with('flashMessageError','Unable to updated merchant');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->merchant->delete($id);
        return redirect('admin/merchant')->with('flashMessageSuccess','The merchant successfully deleted.');
    }
    
    public function getMerchantDetails(Request $request)
    {
        $merchant_id    = $request['merchant_id'];
        $member_id      = $request['member_id'];
        
        $this->response['merchant'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getMerchantVehicleInfo($merchant_id);
        $this->response['driver']   = $this->getMerchantDriverInfo($merchant_id);
        
        return response($this->response,200);
    }
}
