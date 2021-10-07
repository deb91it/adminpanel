<?php namespace App\Repositories\Admin\Merchant;
use App\DB\Admin\Merchant;
use App\DB\Admin\Member;
use App\DB\Admin\PaymentDetails;
use App\DB\Admin\PlansAssign;
use App\DB\Permission;
use DB;
use Auth;
use Datatables;
use Illuminate\Database\Eloquent\Model;


class EloquentMerchantRepository implements MerchantRepository
{
    protected $merchant;

    function __construct(Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getAll() method.
    }

    public function getById($id, $status = 1)
    {
        // TODO: Implement getById() method.
    }

    public function create($inputs)
    {
        // TODO: Implement create() method.
    }

    public function update($input, $id)
    {
        $merchant = Merchant::where('member_id', $id)->first();
        $walletProvider = $input['wallet_provider_bank'];
        $account_name = $input['account_holder_name_bank'];
        $account_number = $input['account_number_bank'];
        if ($input['payment_type'] == 1){
            $walletProvider = $input['wallet_provider_mobile'];
            $account_name = $input['account_holder_name_mobile'];
            $account_number = $input['account_number_mobile'];
        }
        $profile_pic=$merchant->profile_pic;
        $pic_mime_type=$merchant->pic_mime_type;
        $profile_pic_url=$merchant->profile_pic_url;
        $front_image=$merchant->front_image;
        $front_image_url=$merchant->front_image_url;
        if ($input->hasfile('profile_pic')) {
            $save_path = public_path('resources/profile_pic/');
            $file = $input->file('profile_pic');
            $image_name = $input['first_name']."-".$input['last_name']."-".time()."-".$file->getClientOriginalExtension();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();

            //Delete existing image
            if (\File::exists($save_path.$merchant->profile_pic))
            {
                \File::delete($save_path.$merchant->profile_pic);
            }

            //Update DB Field
            $profile_pic      = $image_name;
            $pic_mime_type    = $image_mime;
            $profile_pic_url    = asset('resources/profile_pic/'.$image_name);
        }
        if ($input->hasfile('front_image')) {
            $save_path = public_path('resources/front_image/');
            $file = $input->file('front_image');
            $image_name = $input['first_name']."-".$input['last_name']."-".time()."-".$file->getClientOriginalExtension();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();
            //Delete existing image
            if (\File::exists($save_path.$merchant->front_image))
            {
                \File::delete($save_path.$merchant->front_image);
            }

            //Update DB Field
            $front_image      = $image_name;
            $front_image_url    = asset('resources/front_image/'.$image_name);
        }
        if ($merchant->merchant_code == 0) {
            $merchant->merchant_code   = strtoupper(getUniqueMerchantCode(8));
        }
        $merchant->first_name   = $input['first_name'];
        $merchant->last_name   = $input['last_name'];
        $merchant->hub_id   = $input['hub_id'];
        $merchant->profile_pic  = $profile_pic;
        $merchant->pic_mime_type      = $pic_mime_type;
        $merchant->profile_pic_url    = $profile_pic_url;
        $merchant->id_type     = $input['id_type'];
        $merchant->id_number     = $input['id_number'];
        $merchant->front_image     = $front_image;
        $merchant->front_image_url     = $front_image_url;
        $merchant->operator_name     = $input['operator_name'];
        $merchant->operator_number     = $input['operator_number'];
        $merchant->operator_email     = $input['operator_email'];
        $merchant->business_name     = $input['business_name'];
        $merchant->media_link     = $input['media_link'];
        $merchant->address     = $input['address'];
        $merchant->status      = 1;
        $merchant->updated_at  = date('Y-m-d H:i:s');
        $merchant->edited_by   = !empty(Auth::user()) ? get_logged_user_id() : 0;
        if ($merchant->save()) {
            $this->deleteMerchantPaymentDetails($merchant->id);
            $this->savePaymentDetails($input, $merchant->id);
            return $merchant->id;
        }
        return 0;
    }
    public function savePaymentDetails($input, $merchantId)
    {
        $walletProvider = $input['wallet_provider_bank'];
        $account_name = $input['account_holder_name_bank'];
        $account_number = $input['account_number_bank'];
        if ($input['payment_type'] == 1){
            $walletProvider = $input['wallet_provider_mobile'];
            $account_name = $input['account_holder_name_mobile'];
            $account_number = $input['account_number_mobile'];
        }
        if ($input['payment_type'] == 1 || $input['payment_type'] == 2)
        {
            $payment = new PaymentDetails();
            $payment->merchant_id = $merchantId;
            $payment->payment_type = $input['payment_type'];
            $payment->wallet_provider = $walletProvider;
            $payment->account_holder_name = $account_name;
            $payment->account_number = $account_number;
            $payment->bank_account_type = $input['bank_account_type'];
            $payment->bank_account_type = $input['bank_account_type'];
            $payment->bank_brunch_name = $input['bank_brunch_name'];
            $payment->bank_routing_number = $input['bank_routing_number'];
            $payment->created_at = date('Y-m-d H:i:s');
            $payment->save();
        }
        elseif ($input['payment_type'] == 3) {
            $payment = new PaymentDetails();
            $payment->merchant_id = $merchantId;
            $payment->payment_type = $input['payment_type'];
            $payment->wallet_provider = $input['wallet_provider_mobile'];
            $payment->account_holder_name = $input['account_holder_name_mobile'];
            $payment->account_number = $input['account_number_mobile'];
            $payment->created_at = date('Y-m-d H:i:s');
            $payment->save();

            $payment = new PaymentDetails();
            $payment->merchant_id = $merchantId;
            $payment->payment_type = $input['payment_type'];
            $payment->wallet_provider = $input['wallet_provider_bank'];
            $payment->account_holder_name = $input['account_holder_name_bank'];
            $payment->account_number = $input['account_number_bank'];
            $payment->bank_account_type = $input['bank_account_type'];
            $payment->bank_account_type = $input['bank_account_type'];
            $payment->bank_brunch_name = $input['bank_brunch_name'];
            $payment->bank_routing_number = $input['bank_routing_number'];
            $payment->created_at = date('Y-m-d H:i:s');
            $payment->save();
        }else{
            $payment = new PaymentDetails();
            $payment->merchant_id = $merchantId;
            $payment->payment_type = $input['payment_type'];
            $payment->created_at = date('Y-m-d H:i:s');
            $payment->save();
        }
    }

    public function deleteMerchantPaymentDetails($merchant_id)
    {
        $del = PaymentDetails::where('merchant_id',$merchant_id)->get();
        if (!empty($del))
        {
            foreach ($del as $val)
            {
                PaymentDetails::where("id", $val->id)->delete();
            }
            return "Deleted";
        }
        return "Didn't found any data";
    }

    public function delete($id)
    {
        DB::table('members')
            ->where('id', $id)
            ->update(['can_login' => 0, 'is_active' => 0]);

        DB::table('merchants')
            ->where('member_id', $id)
            ->update([
                'status' => 0,
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => get_logged_user_id()
            ]);
        $merchant = Merchant::where('member_id',$id)->first();
        if(!empty($merchant))
        {
            $query = DB::table('plan_assign_to_merchant')
                ->select('*')
                ->where('merchant_id', $merchant->id)
                ->get();
            foreach ($query as $value){
                $plansassign = PlansAssign::find($value->id);
                $plansassign->status     = 0;
                $plansassign->save();

            }
        }
        return true;
    }


    public function destroy($id)
    {
        // TODO: Implement destroy() method.
        DB::table('members')
            ->where('id', $id)
            ->delete();

        DB::table('merchants')
            ->where('member_id', $id)
            ->delete();

    }

    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }

    public function getUserDetails($member_id)
    {
        return $this->merchant->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $merchant_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getReportPaginated($request){

        $start_date = '';
        $end_date = '';
        $currentTime = date("Y-m-d H:i:s");
        $date_range = $request->get('columns')[9]['search']['value'];

        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (! date_validate($start_date)  || ! date_validate($end_date)) {
                $start_date = '';
                $end_date = '';
            }
        }

        $query = DB::table('members as m')
            ->select('m.id as id', 'm.email as m_email',
                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS full_name'),
                DB::raw('(
                CASE
                    WHEN merch.created_at between "'.date("Y-m-d H:i:s",strtotime("-6 hours")).'" AND "'.$currentTime.'" AND merch.status = 0 THEN 1 ELSE 0    
                END
                )as new_time'),
                'm.mobile_no as m_mobile',
                'm.username as m_username',
                'm.unique_id',
                'merch.status as merch_status',
                'm.can_login as m_canlogin',
                'r.role_name as r_rolename',
                'merch.business_name',
                'merch.merchant_code',
                'merch.address',
                'h.hub_name',
                'merch.created_at as joining_date','merch.id as merchant_id','pa.status as has_plan','pa.id as pa_plan'
            )->join('merchants as merch', 'merch.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'merch.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->leftjoin('hub as h', 'h.id', '=', 'merch.hub_id')
            ->leftjoin(DB::raw("(select * from plan_assign_to_merchant
                    where status = 1 ORDER BY id DESC) as pa"), 'pa.merchant_id', '=', 'merch.id')
            ->orderBy('merch.id','desc')
            ->groupBy('merch.id')
            ;
//        if (get_admin_hub_id() > 0) {
//            $query = $query->where('merch.hub_id', get_admin_hub_id());
//        }
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('merch.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }

        return Datatables::of($query)
            ->filterColumn('full_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(merch.first_name, merch.last_name) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('m_email', function($query, $keyword) {
                $query->whereRaw("m.email like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('m_mobile', function($query, $keyword) {
                $query->whereRaw("m.mobile_no like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('business_name', function($query, $keyword) {
                $query->whereRaw("merch.business_name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('merchant_code', function($query, $keyword) {
                $query->whereRaw("merch.merchant_code like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="'.route('admin.merchant.edit',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="'.route('admin.merchant.inactive',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Deactivate"><i class="fa fa-power-off"></i></a>
                    <a href="'.route('admin.member.profile',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Profile"><i class="fa fa-address-card-o"></i></a>
                    <a href="'.route('admin.plan-assign.edit',array($user->merchant_id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit Plans"><i class="fa fa-th-list"></i></a>
                    <a href="'.route('admin.admin-user.change.password',array($user->id)).'?route_param=merchant" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Change Password"><i class="fa fa-cogs"></i></a>
                    <a href="'.route('admin.deliverys').'?merchant_id='.$user->merchant_id.'" target="_blank" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Merchant Delivery List"><i class="fa fa-ship"></i></a>
                    <span onclick="approveMerchant('.$user->merchant_id.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Approve Merchant"><i class="fa fa-check-circle"></i></span>
                    <span onclick="deleteMerchant('.$user->id.')" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete Merchant"><i class="fa fa-trash"></i></span>
                    ';
            })
            ->make(true);
    }

    public function store($input, $member_id)
    {
        $walletProvider = $input['wallet_provider_bank'];
        $account_name = $input['account_holder_name_bank'];
        $account_number = $input['account_number_bank'];
        if ($input['payment_type'] == 1){
            $walletProvider = $input['wallet_provider_mobile'];
            $account_name = $input['account_holder_name_mobile'];
            $account_number = $input['account_number_mobile'];
        }
        $profile_pic='';
        $pic_mime_type='';
        $profile_pic_url='';
        $front_image='';
        $front_image_url='';
        if ($input->hasfile('profile_pic')) {
            $save_path = public_path('resources/profile_pic/');
            $file = $input->file('profile_pic');
            $image_name = $input['first_name']."-".$input['last_name']."-".time().".".$file->getClientOriginalExtension();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();


            //Update DB Field
            $profile_pic      = $image_name;
            $pic_mime_type    = $image_mime;
            $profile_pic_url    = asset('resources/profile_pic/'.$image_name);
        }
        if ($input->hasfile('front_image')) {
            $save_path = public_path('resources/front_image/');
            $file = $input->file('front_image');
            $image_name = $input['first_name']."-".$input['last_name']."-".time().".".$file->getClientOriginalExtension();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();


            //Update DB Field
            $front_image      = $image_name;
            $front_image_url    = asset('resources/front_image/'.$image_name);
        }

            $merchant = new Merchant();
            $merchant->merchant_code   = strtoupper(getUniqueMerchantCode(8));
            $merchant->first_name   = $input['first_name'];
            $merchant->last_name   = $input['last_name'];
            $merchant->hub_id   = $input['hub_id'];
            $merchant->profile_pic  = $profile_pic;
            $merchant->pic_mime_type      = $pic_mime_type;
            $merchant->profile_pic_url    = $profile_pic_url;
            $merchant->id_type     = $input['id_type'];
            $merchant->id_number     = $input['id_number'];
            $merchant->front_image     = $front_image;
            $merchant->front_image_url     = $front_image_url;
            $merchant->operator_name     = $input['operator_name'];
            $merchant->operator_number     = $input['operator_number'];
            $merchant->operator_email     = $input['operator_email'];
            $merchant->business_name     = $input['business_name'];
            $merchant->media_link     = $input['media_link'];
            $merchant->address     = $input['address'];
            $merchant->member_id     = $member_id;
            $merchant->created_at   = date('Y-m-d H:i:s');
            $merchant->created_by   = !empty(Auth::user()) ? get_logged_user_id() : 0;
            $merchant->status  = isset($input['status']) ? $input['status'] : 1;
            if ($merchant->save()) {
                $payment = new PaymentDetails();
                $payment->merchant_id = $merchant->id;
                $payment->payment_type = $input['payment_type'];
                $payment->wallet_provider = $walletProvider;
                $payment->account_holder_name = $account_name;
                $payment->account_number = $account_number;
                $payment->bank_account_type = $input['bank_account_type'];
                $payment->bank_account_type = $input['bank_account_type'];
                $payment->bank_brunch_name = $input['bank_brunch_name'];
                $payment->bank_routing_number = $input['bank_routing_number'];
                $payment->created_at = date('Y-m-d H:i:s');
                $payment->save();
                return $merchant->id;
            }


        return 0;
    }
    
    public function findOrThrowException($id)
    {
        $data = DB::table('members as m')
            ->select($this->getSelectItemDuringEdit())
            ->join('merchants as merch', 'merch.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'merch.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->leftjoin('payment_details as pay_details','pay_details.merchant_id','=','merch.id')
            ->where('m.id', $id)
            ->first();

        $paymentInfo = DB::table('payment_details as pay_details')->select(
            'pay_info.payment_name','pay_info.id',
            'pay_details.*'
        )
            ->leftjoin('payment_info as pay_info', 'pay_details.wallet_provider', '=', 'pay_info.id')
            ->where('pay_details.merchant_id', getMerchantIdByMemberId($id))
            ->orderBy('pay_details.payment_type','ASC')
            ->get();
        $data->payment_details = $paymentInfo;
        return $data;

    }

    protected function getSelectItemDuringEdit()
    {
        return [
            'm.id as member_id', 'm.username', 'm.email', 'm.mobile_no', 'm.can_login', 'm.is_active',
            'merch.*',
            'r.id as role_id', 'r.role_name',
            'pay_details.payment_type'

         //   DB::raw('(SELECT COUNT(id) FROM vehicles WHERE Merchant_id = merch.id) as no_of_vehicle'),
          //  DB::raw('(SELECT COUNT(id) FROM drivers WHERE Merchant_id = merch.id) as no_of_driver')
        ];
    }

    public function exportFile($request)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        DB::setFetchMode(\PDO::FETCH_ASSOC);
//        $query = DB::table('members as m')
//            ->select(
//                'merch.merchant_code',
//                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS Name'),
//                'merch.business_name',
//                'merch.address',
//                'h.hub_name',
//                'm.email as Email',
//                'm.mobile_no as Mobile', 'm.username as Username',
//                DB::raw("(SELECT GROUP_CONCAT(p.plan_name) WHERE p.plan_type = 'DELIVERED') AS delivery_plan"),
//                DB::raw("(SELECT GROUP_CONCAT(p.plan_name) WHERE p.plan_type = 'RETURNED') AS returned_plan"),
//                'merch.status as Status',
//                'merch.id_number as nid',
//                'merch.media_link as media_link',
//                'm.can_login as Can_login', 'merch.created_at as Joining_date')
//            ->join('merchants as merch', 'merch.member_id', '=', 'm.id')
//            ->join('role_member as rm', 'rm.member_id', '=', 'merch.member_id')
//            ->join('roles as r', 'r.id', '=', 'rm.role_id')
//            ->leftJoin('plan_assign_to_merchant as pam', 'pam.merchant_id', '=', 'm.id')
//            ->leftJoin('plans as p', 'pam.plan_id', '=', 'p.id')
//            ->leftJoin('hub as h', 'merch.hub_id', '=', 'h.id');
//        if ($start_date != '' && $end_date != '') {
//            $query = $query->whereBetween('merch.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
//        }


        $query = DB::table('members as m')
            ->select(
                'merch.merchant_code',
                DB::raw('CONCAT(merch.first_name, " ", merch.last_name) AS Name'),
                'merch.business_name',
                'merch.address',
                'h.hub_name',
                'm.email as Email',
                'm.mobile_no as Mobile',
                'm.username as Username',
                DB::raw("(
                SELECT 
                    GROUP_CONCAT(p.plan_name)
                FROM
                    plans AS p
                        JOIN
                    plan_assign_to_merchant AS pam ON p.id = pam.plan_id
                WHERE
                    p.plan_type = 'DELIVERED'
                        AND pam.merchant_id = merch.id
                ) AS DELIVERY_PLAN"),
                DB::raw("(
                SELECT 
                    GROUP_CONCAT(p.plan_name)
                FROM
                    plans AS p
                        JOIN
                    plan_assign_to_merchant AS pam ON p.id = pam.plan_id
                WHERE
                    p.plan_type = 'RETURNED'
                        AND pam.merchant_id = merch.id
                ) AS RETURNED_PLAN"),
                'merch.status as Status',
                'merch.id_type as ID_TYPE',
                'merch.id_number as NID',
                'merch.media_link as media_link',
                'm.can_login as Canlogin',
                'r.role_name as Role',
                'merch.created_at as Joining_date'
            )->join('merchants as merch', 'merch.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'merch.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->leftJoin('hub as h', 'merch.hub_id', '=', 'h.id');
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('merch.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }
        $data = $query->get();
        return $data;
    }

    public function getVehicleByMemberId($request, $id)
    {
        return  DB::table('merchants as merch')
            ->select('v.model', 'v.license_plate')
            ->join('vehicles as v', 'v.Merchant_id', '=', 'merch.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getDriverByMemberId($request, $id)
    {
        return  DB::table('merchants as merch')
            ->select('d.first_name as driver_first_name', 'd.last_name as driver_last_name', 'dc.is_verified as license_vefication_status')
            ->join('drivers as d', 'd.Merchant_id', '=', 'merch.id')
            ->leftJoin('driving_license as dc', 'dc.driver_id', '=', 'd.id')
            ->where('merch.member_id', $id)
            ->get();
    }

    public function getMerchantList()
    {
        return ['' => 'Select merchant']
            + DB::table('merchants as a')
                ->select(DB::raw('CONCAT(a.first_name, " ", a.last_name, " - ", b.mobile_no ) AS Merchant'), 'a.id')
                ->join('members as b', 'b.id', '=', 'a.member_id')
                ->orderBy('a.id', 'asc')
                // ->where(['b.is_active' => 1])
                ->lists('Merchant', 'id');
    }

    public function getAgentList()
    {
        // TODO: Implement getAgentList() method.
    }

    public function approve($request)
    {
        $merchant = Merchant::find($request->merchant_id);
        $merchant->status = !empty($request->approval_val) ? $request->approval_val : 0;
        $merchant->cod_percentage = !empty($request->cod_percentage) ? $request->cod_percentage : 0;
        $merchant->cod_limit_amount = !empty($request->cod_limit_amount) ? $request->cod_limit_amount : 0;
        $merchant->hub_id = !empty($request->hub_id) ? $request->hub_id :get_admin_hub_id();
        if ($merchant->save())
        {
            $member = Member::find($merchant->member_id);
            $member->can_login = !empty($request->approval_val) ? $request->approval_val : 0;
            $member->save();
            return $merchant->id;
        }
        return 0;

    }

}
