<?php namespace App\Repositories\Admin\Member;

use App\DB\Admin\Member;

class EloquentMemberRepository implements MemberRepository
{
    protected $member;

    // This array will contain user type who has to need unique id
    protected $has_unique_id;
    function __construct(Member $member)
    {
        $this->member = $member;
        $this->has_unique_id = [ '2', '3', '4'];
    }

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllmember($status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getAllmember() method.
    }

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getMemberPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getMemberPaginated() method.
    }

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function create($input, $user_type, $model_id, $role_id)
    {
        $member = $this->createMemberStub($input, $user_type, $model_id);
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
    private function createMemberStub($input, $user_type, $model_id)
    {
        $member = new Member();
        if (in_array($user_type, $this->has_unique_id)) {
            $member->unique_id   = member_unique_id_generator($user_type, $input['mobile_no']);
        }
        $member->username   = isset($input['username']) ? $input['username'] : '';
        $member->unique_id   = isset($input['unique_id']) ? $input['unique_id'] : null;
        $member->email      = isset($input['email']) ? $input['email'] : '';
        $member->mobile_no  = $input['mobile_no'];
        $member->password   = bcrypt($input['password']);
        $member->salt       = "";
        $member->model_id   = $model_id;
        $member->is_active  = isset($input['is_active']) ? $input['is_active'] : 1;
        $member->can_login  = isset($input['can_login']) ? $input['can_login'] : 1;
        $member->user_type  = $user_type;
        $member->activation_code  = 0;
        $member->activation_code_expire  = date('Y-m-d H:i:s');
        return $member;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrThrowException($id)
    {
        $member = $this->member->find($id);
        if (! is_null($member)) return $member;

        throw new GeneralException('That group does not exist.');
    }

    /**
     * @param $id
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function update($input, $id, $roles)
    {
        $member = $this->findOrThrowException($id);
        if (in_array($member->user_type, $this->has_unique_id)) {
            $member->unique_id   = member_unique_id_generator($member->user_type, $input['mobile_no']);
        }
        $member->username   = isset($input['username']) ? $input['username'] : '';
        $member->email      = isset($input['email']) ? $input['email'] : '';
        $member->unique_id   = isset($input['unique_id']) ? $input['unique_id'] : null;
        $member->mobile_no  = $input['mobile_no'];
       // $member->password   = bcrypt($input['password']);
        // $member->salt       = "";
        $member->is_active  = isset($input['is_active']) ? $input['is_active'] : 1;
        $member->can_login  = isset($input['can_login']) ? $input['can_login'] : 1;
        if( $member->save()){
            if ($roles !== null) {
                $member->roles()->sync([$roles]);
            }
            return true;
        }
        throw new GeneralException('There was a problem updating this permission group. Please try again.');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
