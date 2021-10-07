<?php namespace App\Repositories\Admin\Permission;

interface PermissionRepository
{
    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllPermission($status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getPermissionPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc');
    

    public function getReportPaginated($request);

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function create($input);

    /**
     * @param $id
     * @return mixed
     */
    public function findOrThrowException($id);

    /**
     * @param $id
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function update($id, $input);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

}
