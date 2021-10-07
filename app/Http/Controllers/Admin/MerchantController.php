<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\Hub;
use App\DB\Admin\Member;
use App\DB\Admin\PaymentInfo;
use App\DB\Merchant;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Merchant\MerchantRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\MerchantRequest;
use App\Http\Requests\Admin\MerchantEditRequest;
use Excel;
use DB;
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

    /**
     * @var DriverRepository
     */
    protected $merchant;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * MerchantController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        MerchantRepository $merchant
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->merchant = $merchant;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
    public function index(Request $request)
    {
        return view('admin.merchant.index');
    }

    public function postExportFile(Request $request)
    {
        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-Merchant-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-Merchant-from-' . $start_date . '-To-' . $end_date;
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

    public function create()
    {
        $hubs = Hub::pluck('hub_name','id')->toArray();
        $bankInfo = PaymentInfo::where(['is_bank'=>1,'status'=>1])->pluck('payment_name','id')->toArray();
        $mobile = PaymentInfo::where(['is_bank'=>0,'status'=>1])->pluck('payment_name','id')->toArray();
        return view('admin.merchant.create',compact('bankInfo','mobile','hubs'))
            ->withRoles($this->roles->getLists())
            ->withLanguages($this->address->getLanguageList())
            ->withCountries($this->address->getAllCountries())
            ->withBankInfo($bankInfo)
            ->withMobile($mobile)
            ;
    }

    public function registration()
    {
        $hubs = Hub::pluck('hub_name','id')->toArray();
        $bankInfo = PaymentInfo::where(['is_bank'=>1,'status'=>1])->pluck('payment_name','id')->toArray();
        $mobile = PaymentInfo::where(['is_bank'=>0,'status'=>1])->pluck('payment_name','id')->toArray();
        return view('admin.merchant-registration.create',compact('bankInfo','mobile','hubs'))
            ->withRoles($this->roles->getLists())
            ->withLanguages($this->address->getLanguageList())
            ->withCountries($this->address->getAllCountries())
            ->withBankInfo($bankInfo)
            ->withMobile($mobile)
            ;
    }

    public function successMsg(){
        return view('admin.merchant-registration.success');
    }

    public function store(MerchantRequest $request)
    {
        $role_id = 3;

       /* if ($request->get('has_company') == 1) {
            $role_id = 11;
        }*/
        $member_id = $this->member->create($request, $user_type = 2, $model_id = 3, $role_id);
        if ($member_id > 0) {
            $merchant_id = $this->merchant->store($request, $member_id);
            if ($merchant_id > 0) {
                return redirect('admin/merchant')->with('flashMessageSuccess','The Merchant has successfully created !');
            }
        }
        return redirect('admin/merchant')->with('flashMessageError','Unable to create Merchant');
    }

    public function registrationStore(MerchantRequest $request)
    {
        DB::beginTransaction();

        try {
            $role_id = 3;

            /* if ($request->get('has_company') == 1) {
                 $role_id = 11;
             }*/
            $request['can_login'] = 0;
            $member_id = $this->member->create($request, $user_type = 2, $model_id = 3, $role_id);
            if ($member_id > 0) {
                $request['status'] = 0;
                $this->merchant->store($request, $member_id);
            }

            DB::commit();
            // all good
            return redirect('merchant/completion')->with('flashMessageSuccess','The Merchant has successfully created !');
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect('merchant/registration')->with('flashMessageError', $e->getMessage());
        }
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
        $bankInfo = PaymentInfo::where(['is_bank'=>1,'status'=>1])->pluck('payment_name','id')->toArray();
        $mobile = PaymentInfo::where(['is_bank'=>0,'status'=>1])->pluck('payment_name','id')->toArray();
        $merchant = $this->merchant->findOrThrowException($id);
        return view('admin.merchant.edit',compact('bankInfo','mobile','hubs'))
            ->withMerchant($merchant)
            ->withRoles($this->roles->getLists())
            ;
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
        $role_id = 3;
        /*if ($request->get('has_company') == 1) {
            $role_id = 11;
        }*/

        $member = $this->member->update($request, $member_id, $role_id);
        if ($member) {
            $merchant = $this->merchant->update($request, $member_id);
            if ($merchant) {
                return redirect('admin/merchant')->with('flashMessageSuccess','The Merchant successfully updated.');
            }
        }
        return redirect('admin/merchant')->with('flashMessageError','Unable to updated Merchant');
    }

    public function merchantApproval(Request $request)
    {
        $merchants = Merchant::find($request->merchant_id);
        $mem = Member::find($merchants->member_id);
        $hubs = Hub::all();
        if (!empty($merchants)) {
            return response()->json(['success' => true, 'merchant' => $merchants, 'member' =>$mem, 'hubs' => $hubs,'path' => asset('')]);
        }
    }

    public function merchantApprovalStore(Request $request)
    {
        $merchants = $this->merchant->approve($request);
        if ($merchants > 0) {
            return redirect('admin/merchant')->with('flashMessageSuccess','The Approval has successfully completed !');
        }
        return redirect('admin/merchant')->with('flashMessageError','Unable to approve this Merchant');
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
        return redirect('admin/merchant')->with('flashMessageSuccess','The Merchant successfully disabled.');
    }

    public function delete($id)
    {
        $this->merchant->destroy($id);
        return redirect('admin/merchant')->with('flashMessageSuccess','The Merchant successfully deleted.');
    }
    
    public function getMerchantDetails(Request $request)
    {
        $merchant_id    = $request['Merchant_id'];
        $member_id      = $request['member_id'];
        
        $this->response['Merchant'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getMerchantVehicleInfo($merchant_id);
        $this->response['driver']   = $this->getMerchantDriverInfo($merchant_id);
        
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
