<?php namespace App\Repositories\Api\Member;

use App\DB\Api\DeviceRegistration;
use App\DB\Member;

/**
 * Class EloquentMemberRepository
 * @package App\Repositories\Member
 */
class EloquentMemberRepository implements MemberRepository
{
    /**
     * @var Member
     */
    protected $member;
    
    /**
     * EloquentMemberRepository constructor.
     * @param Member $member
     */
    function __construct(Member $member)
    {
        $this->member = $member;
    }
    /**
     * @param array $ar_filter_params
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @param $id
     * @param int $status
     * @return mixed
     */
    public function getById($id, $status = 1)
    {
        // TODO: Implement getById() method.
    }

    /**
     * @param $inputs
     * @return mixed
     */
    public function create($input, $user_type, $model_id, $role_id, $activation_code = '')
    {
        $member = $this->createMemberStub($input, $user_type, $model_id, $activation_code);
        if ($member->save()) {
            //Attach new roles
            $member->roles()->sync([$role_id]);
            return $member->id;
        }
        return false;
    }

    /**
     * @param $input
     * @return mixed
     */
    private function createMemberStub($input, $user_type, $model_id, $activation_code)
    {
        $valid_till = Date('Y-m-d H:i:s', strtotime("+72 hours"));
        $member = new Member();
        $member->username           = isset($input['username']) ? $input['username'] : '';
        $member->email              = isset($input['email']) ? $input['email'] : '';
        $member->mobile_no          = $input['mobile_no'];
        $member->password           = bcrypt($input['password']);
        $member->salt               = "";
        $member->model_id           = $model_id;
        $member->is_active          = 0;
        $member->can_login          = 0;
        $member->activation_code    = $activation_code;
        $member->activation_code_expire    = $valid_till;
        $member->user_type          = $user_type;
        return $member;
    }

    /**
     * @param $id
     * @param $inputs
     * @return mixed
     */
    public function update($id, $inputs)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return ['details' => 'Nothing found here'];
    }


    public function createMemberByDriverWithMinInfoForMerchant($input)
    {
        
        $member                     = new Member();
        $member->username           = '';
        $member->email              = '';
        $member->mobile_no          = $input['mobile_no'];
        $member->password           = bcrypt("ezzyr@123456");
        $member->salt               = '';
        $member->model_id           = 4;
        $member->is_active          = 0;
        $member->can_login          = 0;
        $member->user_type          = 3;

        if ($member->save()) {
            //Attach new roles
            $member->roles()->sync([4]);
            return $member->id;
        }

        return 0;          
    }
    
    public function createMemberWithDriverInfoAsMerchant($member_id, $driver_id)
    {
        
        $dmemberData = Member::where('id', $member_id)->first();
        $hasMerchantMemberId = $this->checkMerchantMemberWithMobile($dmemberData['mobile_no']);
        if( $hasMerchantMemberId == 0)
        {
            $member                     = new Member();
            $member->username           = isset($dmemberData['username']) ? $dmemberData['username'] : '';
            $member->email              = isset($dmemberData['email']) ? $dmemberData['email'] : '';
            $member->mobile_no          = $dmemberData['mobile_no'];
            $member->password           = $dmemberData['password'];
            $member->salt               = isset($dmemberData['salt']) ? $dmemberData['salt'] : '';
            $member->model_id           = 4;
            $member->is_active          = 0;
            $member->can_login          = 0;
            $member->user_type          = 3;

            if ($member->save()) {
                //Attach new roles
                $member->roles()->sync([4]);
                return $member->id;
            }

            return 0;  
        }

        
        return $hasMerchantMemberId;        
    }

    public function checkMerchantMemberWithMobile($mobile_no){
        

        $dmemberData = Member::where(['mobile_no'      => $mobile_no, 'user_type'      => 3] )->first();
        
        if (!empty($dmemberData) && $dmemberData['id'] > 0) {
            return $dmemberData['id'];
        }

        return 0;
    }

    public function storeDeviceID($member_id, $device_id)
    {
        // TODO: Implement storeDeviceID() method.
        $data = DeviceRegistration::where("member_id",$member_id)->first();
        if (empty($data)) {
            $data = new DeviceRegistration();
        }
        $data->device_id = $device_id;
        $data->member_id = $member_id;
        $data->status = 1;
        $data->created_at = date("Y-m-d H:i:s");
        if ($data->save()) {
            return $data->device_id;
        }
        return '';
    }
}
