<?php namespace App\Repositories\Admin\PermissionGroup;

interface PermissionGroupRepository
{
    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllPermissionGroup($status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getPermissionGroupPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc');

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

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getLists($status = 1, $order_by = 'id', $sort = 'asc');
}
