<?php namespace App\Repositories\Admin\Role;

use App\DB\Admin\Role;
use App\DB\Admin\Permission;
use App\DB\Admin\PermissionGroup;
use App\DB\Admin\RolePermission;
use DB;
/**
 * Class EloquentRoleRepository
 * @package App\Repositories\Admin\Role
 */
class EloquentRoleRepository implements RoleRepository
{
    /**
     * @var Role
     */
    protected $role;
    protected $perm_group;
    protected $role_permission;

    /**
     * @param Role $role
     */
    function __construct(Role $role,PermissionGroup $perm_group,RolePermission $role_permission)
    {
        $this->role = $role;
        $this->perm_group = $perm_group;
        $this->role_permission = $role_permission;
    }


    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllRole($status = 1, $order_by = 'id', $sort = 'asc')
    {
        return $this->role->where('status', $status)->orderBy($order_by, $sort)->get();
    }

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getLists($status = 1, $order_by = 'id', $sort = 'asc') {
        return $this->role->where('status', $status)->whereNotIn('id', [2,3])->orderBy($order_by, $sort)->lists('role_name', 'id');
    }

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getRolePaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc')
    {
        return $this->role->where('status', $status)->orderBy($order_by, $sort)->paginate($per_page);
    }

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function create($input, $permissions)
    {
        $role = new Role();
        $role->role_name = $input['role_name'];
        $role->created_by = get_logged_user_id();
        $role->status = 1;
        if($role->save()){
            //Update Role permission table
            $perm_string = ",dashboard,";
            if(count($permissions['permission'])){
                //$perm_string = ",";
                $perm_string = implode(',',$permissions['permission']);
                $perm_string = ','.$perm_string.',';
            }

            //$inserted_id = $role->id;
            $rolePermission = new RolePermission();
            $rolePermission->role_id = $role->id;
            $rolePermission->permissions = $perm_string;
            if($rolePermission->save()){
                return true;
            }
            return flase;
        }
    }

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllGroups($status = 1, $order_by = 'id', $sort = 'asc')
    {
        return $this->perm_group->with('permissions')->where('status', $status)->orderBy($order_by, $sort)->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrThrowException($id)
    {
        $role = $this->role->with('permission')->find($id);
        if (! is_null($role)) return $role;
        throw new GeneralException('That role does not exist.');
    }


    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function update($id, $input, $permissions)
    {
        $role = $this->findOrThrowException($id);
        $role->role_name = $input['role_name'];
        $role->edited_by = get_logged_user_id();

        //Update Role permission table
        $perm_string = ",dashboard,";
        if(count($permissions['permission'])){
            //$perm_string = ",";
            $perm_string = implode(',',$permissions['permission']);
            $perm_string = ','.$perm_string.',';
        }
        $role->permission->permissions = $perm_string;

        if($role->push()){
            return true;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        DB::table('roles')
            ->where('id', $id)
            ->update(['status' => 0, 'deleted_at' => date('Y-m-d H:i:s')]);
        return true;
     }
}
