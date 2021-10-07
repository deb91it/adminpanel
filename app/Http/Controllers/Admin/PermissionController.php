<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Permission\PermissionRepository;
use App\Repositories\Admin\PermissionGroup\PermissionGroupRepository;
use Auth;

class PermissionController extends Controller
{
    /**
     * EloquentPermissionPermissionRepository
     * @var
     */
    protected $permission;
    protected $group;

    public function __construct(PermissionRepository $permission,PermissionGroupRepository $group){
        $this->permission = $permission;
        $this->group = $group;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$perPage = 20;
        return view('admin.permission.index');
            //->withPermissions($this->permission->getPermissionPaginated($perPage));
    }

    public function getDataTableReport(Request $request){   
    return $this->permission->getReportPaginated($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.permission.create')
            ->withGroups($this->group->getLists());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PermissionRequest $request)
    {
        $this->permission->create($request);
        return redirect('admin/permission')->with('flashMessageSuccess','The permission was successfully added.');
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
        return view('admin.permission.edit')
            ->withPermission($this->permission->findOrThrowException($id))
            ->withGroups($this->group->getLists());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PermissionRequest $request, $id)
    {
        $this->permission->update($id,$request);
        return redirect('admin/permission')->with('flashMessageSuccess','The Permission was successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->permission->delete($id);
        return redirect('admin/permission')->with('flashMessageSuccess','The permission was successfully deleted.');
    }
}
