<?php namespace App\Repositories\Api\CommonTask;
use App\DB\Api\Agent;
use App\DB\Api\Member;
use App\DB\Merchant;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class EloquentCommonTaskRepository implements CommonTaskRepository
{

    protected $merchant_id;
    function __construct(Request $request){
        $this->merchant_id = getMerchantId($request->header('Authorization'));
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getEmployeeData($user_id, $user_type)
    {
        $query = '';
        if (!empty($user_type) && $user_type == 2)
        {
            $query = DB::table("merchants as m")
                ->select(
                    'm.*',
                    'm.id as user_id',
                    'm.merchant_code',
                    'mem.id as member_id', 'mem.username', 'mem.email', 'mem.mobile_no', 'mem.unique_id',
                    DB::raw("(SELECT
                                CASE payment_type 
                                WHEN '1' THEN 'Mobile Banking'
                                WHEN '2' THEN 'Bank'
                                WHEN '3' THEN 'Mobile Banking & Bank'
                                WHEN '4' THEN 'CASH'
                                ELSE 'CASH'
                                END) AS payment_type")
                )
                ->join('members as mem', 'mem.id', '=', 'm.member_id')
                ->leftjoin('payment_details as pay_details','pay_details.merchant_id','=','m.id')
                ->where('m.id', $user_id)
                ->first();

            $paymentInfo = DB::table('payment_details as pay_details')->select(
                'pay_info.payment_name','pay_info.id',
                'pay_details.*'
            )
                ->leftjoin('payment_info as pay_info', 'pay_details.wallet_provider', '=', 'pay_info.id')
                ->where('pay_details.merchant_id', ($user_id))
                ->orderBy('pay_details.payment_type','ASC')
                ->get();
            $query->payment_details = $paymentInfo;
        }

        if (!empty($user_type) && $user_type == 1)
        {
            $query = DB::table("riders as r")
                ->select(
                    'r.*',
                    'r.id as user_id',
                    'mem.id as member_id', 'mem.username', 'mem.email', 'mem.mobile_no', 'mem.unique_id'
                )
                ->join('members as mem', 'mem.id', '=', 'r.member_id')
                ->where('r.id', $user_id)
                ->first();
        }

        return $query;
    }

    public function checkCurrentAttendance($employee_id)
    {
        $query = DB::table("sr_attendence")
            ->select("status")
            ->where('employee_id', $employee_id)
            ->whereBetween('attendance_date',[date("Y-m-d 00:00:00"),date("Y-m-d 23:59:59")])
            ->first();
        return $query;
    }

    public function postUploadProfileImage($inputs)
    {
        $save_path = public_path("resources/profile_pic/");
        $file = $inputs->file('_image');
        $image_name = "profile_pic_".$inputs['user_id']."_".time()."_".$file->getClientOriginalName();
        $file->move($save_path, $image_name);
        \Image::make(sprintf($save_path.'%s', $image_name))->save();
        $image_mime = \Image::make($save_path . $image_name)->mime();

        $data =  DB::table('agents')
            ->select('id', 'profile_pic')
            ->where('id', $inputs['user_id'])
            ->first();
        if(empty($data)){
            return false;
        }

        $imageUrl = url("resources/profile_pic")."/".$image_name;

        $exist_img = public_path("resources/profile_pic") . "/" . $data->profile_pic;
        if ($data->profile_pic != '') {
            if (file_exists($exist_img)) {
                unlink($exist_img);
            }
        }

        DB::table('agents')
            ->where(['id' => $data->id])
            ->update([
                'profile_pic'     => $image_name,
                'profile_pic_url' => $imageUrl,
                'pic_mime_type' => $image_mime,
                'updated_at'    => date('Y-m-d H:i:s')
            ]);

        return $imageUrl;
    }

    public function updateProfile($request)
    {
        $member_id = '';
        $profile_pic ='';
        $pic_mime_type ='';
        $profile_pic_url ='';
        if (isset($request['user_type']) && $request['user_type'] == 1)
        {
            $mod = DB::table('riders')
                ->select('id','member_id','profile_pic','pic_mime_type','profile_pic_url','first_name','last_name')
                ->where('id',$request['user_id'])->first();
            $member_id = $mod->member_id;
            $member = Member::find($member_id);
            if (!empty($member))
            {
                if (Hash::check($request['old_password'], $member->password)) {
                    $full_name = explode(" ",$request['full_name']);
                    $mod = Agent::find($mod->id);
                    $mod->date_of_birth = $request['date_of_birth'];
                    $mod->gender = $request['gender'];
                    $mod->first_name = $full_name[0];
                    $mod->last_name = $full_name[1];
                    $mod->save();
                    return $mod->id;
                }
                else{
                    return 'i';
                }
            }
        }
        return '';
    }

    public function updateProfileImage(Request $request)
    {
        $mod = \App\DB\Api\Merchant::find($request->user_id);
        if (isset($request->user_type) && $request->user_type == 1)
        {
            $mod = Agent::find($request->user_id);
        }
        if (empty($mod)){
            return '';
        }
        $profile_pic = $mod->profile_pic;
        $pic_mime_type = $mod->pic_mime_type;
        $profile_pic_url = $mod->profile_pic_url;
        if ($request->hasfile('profile_pic')) {
            $save_path = public_path('resources/profile_pic/');
            $file = $request->file('profile_pic');
            $image_name = $mod->first_name."-".$mod->last_name."-".time()."-".$file->getClientOriginalExtension();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();

            //Delete existing image
            if (\File::exists($save_path.$mod->profile_pic))
            {
                \File::delete($save_path.$mod->profile_pic);
            }

            //Update DB Field
            $profile_pic      = $image_name;
            $pic_mime_type    = $image_mime;
            $profile_pic_url    = asset('resources/profile_pic/'.$image_name);
        }
        $mod->profile_pic = $profile_pic;
        $mod->pic_mime_type = $pic_mime_type;
        $mod->profile_pic_url = $profile_pic_url;
        $mod->save();
        return $mod;
    }

    public function updateMerchantProfile($request)
    {
        $merchant = \App\DB\Api\Merchant::find($this->merchant_id);
        if ($request->hasfile('profilePic')) {
            $save_path = public_path('resources/profile_pic/');
            $file = $request->file('profilePic');
            $image_name = $merchant->business_name."-".time().'.'.$file->getClientOriginalExtension();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();

            //Delete existing image
            if (\File::exists($save_path.$merchant->profile_pic))
            {
                \File::delete($save_path.$merchant->profile_pic);
            }

            //Update DB Field
            $merchant->profile_pic      = $image_name;
            $merchant->pic_mime_type    = $image_mime;
            $merchant->profile_pic_url  = asset('resources/profile_pic/'.$image_name);
        }
        $merchant->business_name = $request->input('businessName');
        if ($merchant->save()) {
            return \App\DB\Api\Merchant::find($this->merchant_id);
        }
        return false;
    }
}
