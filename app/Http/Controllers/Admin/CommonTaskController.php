<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\CommonTask\CommonTaskRepository;
use App\Repositories\Admin\AdminUser\AdminUserRepository;
//use App\Repositories\Admin\Passenger\PassengerRepository;
//use App\Repositories\Admin\Driver\DriverRepository;
use App\Repositories\Admin\Merchant\MerchantRepository;
use App\Repositories\Admin\Agent\AgentRepository;
use Response;
use PDF;
use DB;

class CommonTaskController extends Controller
{
    protected $common_task;
    protected $admin_user;
//    protected $passenger;
//    protected $driver;
    protected $merchant;
    protected $agent;

    function __construct(
        CommonTaskRepository $common_task,
        AdminUserRepository $admin_user,
//        PassengerRepository $passenger,
//        DriverRepository $driver,
        MerchantRepository $merchant,
        AgentRepository $agent
    )
    {
        $this->common_task = $common_task;
        $this->admin_user = $admin_user;
//        $this->passenger = $passenger;
//        $this->driver = $driver;
        $this->merchant = $merchant;
        $this->agent = $agent;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postChangeCanLoginStatus(Request $request)
    {
        $this->common_task->changeCanLoginStatus($request['member_id']);
        print_r([ 'msg' => "Successfully changed !!"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postChangeIsActiveStatus(Request $request)
    {
        $member_id = $request->get('member_id');
        /*
         * Newly added at 26.11.2018
         * Checking if the requested member id is for driver then have to check the driver belongs to merchant
         */
        $driver_id = get_driver_id_by_member_id($member_id);
        if($driver_id > 0) {
           if(DB::table('drivers')->where('id', $driver_id)->value('merchant_id') < 1) {
               $this->response['success'] = false;
               $this->response['msg'] = 'Sorry !! The driver don\'t have merchant !';
               return response($this->response, 200);
           }
        }

        $this->common_task->changeIsActiveStatus($member_id);
        $this->response['success'] = true;
        $this->response['msg'] = 'You have successfully changed the status !';
        return response($this->response, 200);
    }

    public function profileData(Request $request, $id){
        $driver = [];
        $types = $this->getUserTypeStringByType(get_user_type_by_member_id($id));
        $member = $this->{$types}->findOrThrowException($id);
//        dd($member);
//        $vehicle = $this->{$types}->getVehicleByMemberId($request, $id);
//        if($types == 'merchant' || $types == 'agent'){
//            $driver = $this->{$types}->getDriverByMemberId($request, $id);
//        }
        $vehicle = '';

        if ($request->get('has_downlaod')) {
            $pdf = PDF:: loadView('admin.common.profile-pdf', compact('types', 'member', 'vehicle', 'driver'));
            return $pdf->download("{$member->first_name}_{$member->last_name}_profile.pdf");
        }
        return view('admin.common.profile')
        ->withMember($member)
        ->withTypes($types)
        ->withVehicle($vehicle)
        ->withDriver($driver);
    }

    public function profileImageDownload($id) { 
        $types = $this->getUserTypeStringByType(get_user_type_by_member_id($id));
        $member = $this->{$types}->findOrThrowException($id);
        if (! empty($member)) {
            $filepath = public_path('/resources/profile_pic/').$member->profile_pic;
            if (file_exists($filepath)) {
                return Response::download($filepath);
            }            
        }
       return redirect('admin/merchant')->with('flashMessageError','Did not found profile image !');
    }

    public function profileDetailsPdf(){
        $pdf = PDF:: loadView('admin.common.profile');
        return $pdf -> download('invoice.pdf');
    }

    public function deliveryInvoiceDetailsPdf(){
        $pdf = PDF:: loadView('admin.delivery.partial.delivery_invoice_pdf');
        return $pdf -> download('invoice.pdf');
    }

    private function getUserTypeStringByType($types) {
        $type_string = '';
        switch ($types) {
            case 0:
                $type_string = 'admin_user';
                break;
            case 1:
                $type_string = 'agent';
                break;
            case 2:
                $type_string = 'merchant';
                break;
            case 4:
                $type_string = '';
                break;
        }
        return $type_string;
    }
    public function idTypeImageDownload($id) {
        $types = $this->getUserTypeStringByType(get_user_type_by_member_id($id));
        $member = $this->{$types}->findOrThrowException($id);
        if (! empty($member)) {
            $filepath = public_path('/resources/front_image/').$member->front_image;
            if (file_exists($filepath)) {
                return Response::download($filepath);
            }
        }
        return redirect('admin/merchant')->with('flashMessageError','Did not found profile image !');
    }


}
