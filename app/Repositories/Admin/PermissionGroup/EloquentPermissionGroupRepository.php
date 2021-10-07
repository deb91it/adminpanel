<?php namespace App\Repositories\Admin\PermissionGroup;
use App\DB\Admin\PermissionGroup;

class EloquentPermissionGroupRepository implements PermissionGroupRepository
{
    /**
     * @var PermissionGroup
     */
    protected $permissionGroup;

    /**
     * @param PermissionGroup $permissionGroup
     */
    function __construct(PermissionGroup $permissionGroup)
    {
        $this->permissionGroup = $permissionGroup;
    }

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllPermissionGroup($status = 1, $order_by = 'id', $sort = 'asc')
    {
        return $this->permissionGroup->where('status', $status)->orderBy($order_by, $sort)->get();
    }

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getPermissionGroupPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc')
    {
        return $this->permissionGroup->where('status', $status)->orderBy($order_by, $sort)->paginate($per_page);
    }

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function create($input)
    {
        $permissionGroup = new PermissionGroup;
        $permissionGroup->group_name = $input['group_name'];
        $permissionGroup->status = 1;
        $permissionGroup->created_by = get_logged_user_id();
        if($permissionGroup->save()){
            return true;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrThrowException($id)
    {
        $group = $this->permissionGroup->find($id);
        if (! is_null($group)) return $group;

        throw new GeneralException('That group does not exist.');
    }

    /**
     * @param $id
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function update($id, $input)
    {
        $group = $this->findOrThrowException($id);
        $group->group_name = $input['group_name'];
        $group->edited_by = get_logged_user_id();
        if( $group->save()){
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
        $group = $this->permissionGroup->findOrFail($id);
        $group->delete();
    }

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getLists($status = 1, $order_by = 'id', $sort = 'asc') {

        return $this->permissionGroup->where('status', $status)->orderBy($order_by, $sort)->lists('group_name', 'id');
    }
}
