<?php namespace App\Repositories\Admin\Permission;
use App\DB\Admin\Permission;
use DB;
use Datatables;

class EloquentPermissionRepository implements PermissionRepository
{
    /**
     * @var Permission
     */
    protected $permission;

    /**
     * @param Permission $permission
     */
    function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllPermission($status = 1, $order_by = 'id', $sort = 'asc')
    {
        return $this->permission->where('status', $status)->orderBy($order_by, $sort)->get();
    }

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getPermissionPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc')
    {
        return $this->permission->with('permission_group')->where('status', $status)->orderBy($order_by, $sort)->paginate($per_page);
    }

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */

    public function getReportPaginated($request){
        $query = DB::table('permissions as p')
            ->select('p.id','p.display_name', 'p.name', 'pg.group_name')
            ->join('permission_groups as pg', 'pg.id', '=', 'p.permission_group_id');

        return Datatables::of($query)
            ->addColumn('action_col', function ($user) {
                    return '<center><a href="'.route('admin.permission.edit',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                        <a href="'.route('admin.permission.delete',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="fa fa-trash"></i></a></center>';


            })
            
            ->make(true);
    }


    public function create($input)
    {
        $permission = new Permission();
        $permission->name = $input['permission_slug'];
        $permission->display_name = $input['display_name'];
        $permission->permission_group_id = $input['permission_group'];
        $permission->created_by = get_logged_user_id();
        $permission->status = 1;
        if($permission->save()){
            return true;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrThrowException($id)
    {
        $permission = $this->permission->find($id);
        if (! is_null($permission)) return $permission;

        throw new GeneralException('That permission does not exist.');
    }

    /**
     * @param $id
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function update($id, $input)
    {
        $permission = $this->findOrThrowException($id);
        $permission->name = $input['permission_slug'];
        $permission->display_name = $input['display_name'];
        $permission->permission_group_id = $input['permission_group'];
        $permission->edited_by = get_logged_user_id();
        $permission->status = 1;
        if($permission->save()){
            return true;
        }
        throw new GeneralException('There was a problem updating this permission. Please try again.');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $permission = $this->permission->findOrFail($id);
        $permission->delete();
    }
}
