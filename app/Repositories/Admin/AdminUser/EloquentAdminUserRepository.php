<?php namespace App\Repositories\Admin\AdminUser;

use App\DB\Admin\AdminUser;
use App\DB\Admin\Member;
use App\DB\Permission;
use DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class EloquentAdminUserRepository
 * @package App\Repositories\Admin\AdminUser
 */
class EloquentAdminUserRepository implements AdminUserRepository
{
    /**
     * @var AdminUser
     */
    protected $user;
    /**
     * EloquentAdminUserRepository constructor.
     * @param AdminUser $user
     */
    function __construct(AdminUser $user)
    {
        $this->user = $user;
    }


    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllUser($status = 1, $order_by = 'id', $sort = 'asc')
    {
        return $this->user->where('status', $status)->orderBy($order_by, $sort)->get();
    }

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getUserPaginated($per_page, $status = 1, $order_by = 'au.id', $sort = 'asc')
    {
        return DB::table('members as m')
            ->select('m.id as member_id', 'm.email', 'm.mobile_no','m.is_active','m.can_login', 'au.first_name', 'au.last_name', 'au.designation', 'r.role_name','h.hub_name')
            ->join('admin_users as au', 'au.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'au.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->leftjoin('hub as h', 'h.id', '=', 'au.hub_id')
            ->where('m.user_type', 0)
            ->whereNotIn('m.id',[1, 2])
            ->where('au.status', $status)
            ->orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function create($input, $member_id)
    {
        $user = $this->createUserStub($input, $member_id);
        if ($user->save()) {
            return true;
        }
        return false;
    }

    /**
     * @param $input
     * @return mixed
     */

    private function createUserStub($input, $member_id)
    {
        $user = new AdminUser;
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->designation = $input['designation'];
//        $user->designation = $input['designation'];
        $user->member_id = $member_id;
        $user->hub_id = !empty( $input['hub_id'] ) ? $input['hub_id'] : 0;
        $user->status = 1;
        return $user;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrThrowException($id)
    {
        $user =  DB::table('members as m')
            ->select('*','m.id as member_id')
            ->join('admin_users as au', 'au.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'au.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->where('m.id', $id)
            ->where('au.status', 1)
            ->first();
        if (! is_null($user)) return $user;

        throw new GeneralException('That user does not exist.');
    }

    /**
     * @param $id
     * @param $input
     * @param $roles
     * @return bool
     * @throws GeneralException
     */
    public function update($input, $id) {
        $user = AdminUser::where('member_id', $id)->first();
        $user->first_name  = $input['first_name'];
        $user->last_name   = $input['last_name'];
        $user->designation = $input['designation'];
        $user->hub_id = $input['hub_id'];
        if ($user->save()) {
            return true;
        }
        return 0;

        throw new GeneralException('There was a problem updating this user. Please try again.');
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function delete($id) {
        DB::table('members')
            ->where('id', $id)
            ->update(['status' => 0, 'can_login' => 0, 'is_active' => 0]);

        DB::table('admin_users')
            ->where('member_id', $id)
            ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
        return true;
    }

    public function updateProfile($input, $id)
    {
        $adminUser = AdminUser::where('member_id', $id)->first();

        if ($input->hasfile('profile_pic')) {
            $save_path = public_path('resources/profile_pic/');
            $file = $input->file('profile_pic');
            $image_name = $input['first_name']."-".$input['last_name']."-".time()."-".$file->getClientOriginalName();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();

            //Delete existing image
            if (\File::exists($save_path.$adminUser->profile_pic))
            {
                \File::delete($save_path.$adminUser->profile_pic);
            }

            //Update DB Field
            $adminUser->profile_pic      = $image_name;
            $adminUser->pic_mime_type    = $image_mime;
        }

        $adminUser->first_name  = $input['first_name'];
        $adminUser->last_name   = $input['last_name'];
        $adminUser->gender = $input['gender'];
        $adminUser->designation = $input['designation'];
        $adminUser->updated_at = date('Y-m-d H:i:s');

        if ($adminUser->save()) {
            $member = Member::where('id', $id)->first();
            $member->email = $input['email'];
            $member->mobile_no = $input['mobile_no'];
            if ($member->save()) {
                return true;
            }
        }
        return false;
    }

    public function updateUserPassword($input)
    {
        // TODO: Implement updateUserPassword() method.
        $mem = Member::find($input->id);
        $mem->password = Hash::make($input->new_password);
        $mem->save();
    }
}