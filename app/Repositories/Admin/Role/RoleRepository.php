<?php namespace App\Repositories\Admin\Role;

/**
 * Interface RoleRepository
 * @package App\Repositories\Admin\Role
 */
interface RoleRepository
{

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllRole($status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getRolePaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc');
    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getLists($status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllGroups($status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function create($input, $roles);

    /**
     * @param $id
     * @return mixed
     */
    public function findOrThrowException($id);

    /**
     * @param $id
     * @param $input
     * @return mixed
     */
    public function update($id, $input, $permission);


    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);





}
