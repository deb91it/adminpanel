<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests\Admin\PermissionGroupRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\PermissionGroup\PermissionGroupRepository;

class PermissionGroupController extends Controller
{
    /**
     * @var
     */
    protected $permissionGroup;

    /**
     * @param EloquentPermissionGroupRepository $permissionGroup
     */
    function __construct(PermissionGroupRepository $permissionGroup)
    {
        $this->permissionGroup = $permissionGroup;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $per_page = 20;
        return view('admin.permission-group.index')
            ->withGroups($this->permissionGroup->getPermissionGroupPaginated($per_page));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.permission-group.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PermissionGroupRequest $request)
    {
        $this->permissionGroup->create($request);
        return redirect('admin/permission-group')->with('flashMessageSuccess','The group was successfully added.');
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
        return view('admin.permission-group.edit')
            ->withGroup($this->permissionGroup->findOrThrowException($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($id,PermissionGroupRequest $request)
    {
        $this->permissionGroup->update($id,$request);
        return redirect('admin/permission-group')->with('flashMessageSuccess','The group was successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->permissionGroup->delete($id);
        return redirect('admin/permission-group')->with('flashMessageSuccess','The group was successfully deleted.');
    }
}
