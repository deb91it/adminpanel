<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\Hub;
use Dropbox\Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminUser\AdminUserRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Http\Requests\Admin\AdminUserEditRequest;
use App\Http\Requests\Admin\AdminPassChange;
use App\Http\Requests\Admin\EditProfile;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use Illuminate\Support\Facades\Hash;
/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class AdminUserController extends Controller
{

    /**
     * @var EloquentUserRepository
     */
    protected $users;
    /**
     * @var EloquentRoleRepository
     */
    protected $roles;
    protected $member;

    /**
     * @param EloquentUserRepository $users
     * @param EloquentRoleRepository $roles
     */
    function __construct(AdminUserRepository $users, RoleRepository $roles, MemberRepository $member)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->member = $member;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {
        $per_page = 100;
        return view('admin.admin-user.index')
            ->withUsers($this->users->getUserPaginated($per_page));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $hubs = Hub::pluck('hub_name','id')->toArray();
        return view('admin.admin-user.create',compact('hubs'))
        ->withRoles($this->roles->getLists());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(AdminUserRequest $request)
    {
        $member_id = $this->member->create($request, $user_type = 0, $model_id = 1, $request['user_role']);
        if ($member_id > 0) {
            $create_sts = $this->users->create($request, $member_id);
            if ($create_sts)
                return redirect('admin/admin-users')->with('flashMessageSuccess','The user successfully added.');
        }
        return redirect('admin/admin-users')->with('flashMessageError','Unable to add new user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        if (in_array($id, [1, 2])) {
            return redirect('admin/admin-users')->with('flashMessageWarning', 'Sorry! You can not edit the user !');
        }

        $hubs = Hub::get();
        return view('admin.admin-user.edit',compact('hubs'))
            ->withUser($this->users->findOrThrowException($id))
            ->withRoles($this->roles->getLists());
    }
    public function changePasswordByAdmin(Request $request,$id)
    {
        if (in_array($id, [1, 2])) {
            return redirect('admin/admin-users')->with('flashMessageWarning', 'Sorry! You can not edit the user !');
        }
        $routeParam = $request->get('route_param');
        return view('admin.admin-user.change-password-by-admin',compact('id','routeParam'));
    }

    public function updatePasswordByAdmin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'new_password'          => 'required|min:6|max:60',
                'confirm_new_password'  => 'required|min:6|max:60|same:new_password'
            ]);

            if ($validator->fails()) {
                return redirect("admin/admin-user/{$request->id}/change/password")
                    ->withErrors($validator)
                    ->withInput();
            }
//            dd($request->all());
            $this->users->updateUserPassword($request);
            if ($request->has('route_param') && !empty($request->route_param))
            {
                return redirect("admin/{$request->route_param}")->with('flashMessageSuccess','Password successfully updated for this user.');
            }
            return redirect('admin/admin-users')->with('flashMessageSuccess','Password successfully updated for this user.');
        }catch (\Exception $exception)
        {
            return redirect("admin/admin-user/{$request->id}/change/password")->with('flashMessageError','Unable to updated user');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(AdminUserEditRequest $request, $member_id)
    {
        $member = $this->member->update($request, $member_id, $request['user_role']);
        if ($member) {
            $driver = $this->users->update($request, $member_id);
            if ($driver) {
                return redirect('admin/admin-users')->with('flashMessageSuccess','The user successfully updated.');
            }
        }
        return redirect('admin/admin-users')->with('flashMessageError','Unable to updated user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->users->delete($id);
        return redirect('admin/admin-users')->with('flashMessageSuccess','The user successfully deleted.');
    }

    public function changePassword()
    {
       // return view('admin.admin-user.change-password');
        return view('admin.admin-user.change-password');
    }

    public function postChangePassword(Request $inputs)
    {

        $validator = Validator::make($inputs->all(), [
            'current_password'      => "required|min:6",
            'new_password'          => 'required|min:6|max:60|different:current_password',
            'confirm_new_password'  => 'required|min:6|max:60|same:new_password'
        ]);

        if ($validator->fails()) {
            return redirect('admin/change-password')
                ->withErrors($validator)
                ->withInput();
        }

        $info = DB::table('members')
            ->select('password')
            ->where('id', get_logged_user_id())
            ->first();

        if (empty($info)) {
            $validator->getMessageBag()->add('users', 'Invalid user !');

            return redirect('admin/change-password')
                ->withErrors($validator)
                ->withInput();
        }

        if (! Hash::check($inputs->all()['current_password'], $info->password)) {
            $validator->getMessageBag()->add('current_password', 'Does not math current password!');

            return redirect('admin/change-password')
                ->withErrors($validator)
                ->withInput();
        }

        DB::table('members')
            ->where('id', get_logged_user_id())
            ->update(['password' => bcrypt($inputs->all()['new_password'])]);

        return redirect('admin/user-profile')->with('flashMessageSuccess','The user successfully updated.');
    }

    public function getViewProfile()
    {
        $auth_info = DB::table('members as a')
            ->select('a.*','b.*', 'r.role_name')
            ->join('admin_users as b', 'b.member_id', '=', 'a.id')
            ->join('role_member as c', 'c.member_id', '=', 'a.id')
            ->join('roles as r', 'r.id', '=', 'c.role_id')
            ->where('a.id', get_logged_user_id())
            ->first();
            
        return view('admin.admin-user.profile')
            ->withUser($auth_info);
    }

    public function getEditProfile()
    {
        $auth_info = DB::table('members as a')
            ->select('a.*','b.*', 'r.role_name')
            ->join('admin_users as b', 'b.member_id', '=', 'a.id')
            ->join('role_member as c', 'c.member_id', '=', 'a.id')
            ->join('roles as r', 'r.id', '=', 'c.role_id')
            ->where('a.id', get_logged_user_id())
            ->first();

        return view('admin.admin-user.edit_profile')
            ->withUser($auth_info);
    }

    public function postEditProfile(Request $request)
    {
        $id = $this->users->updateProfile($request, get_logged_user_id());
        if ($id > 0) {
            return redirect('admin/user-profile')->with('flashMessageSuccess','Profile successfully updated.');
        }
        return redirect('admin/user-profile')->with('flashMessageError','Unable to updated profile');
    }


}