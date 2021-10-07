<?php namespace App\Repositories\Admin\AdminUser;

/**
 * Interface UserRepository
 * @package App\Repositories\Admin\User
 */
interface AdminUserRepository
{
    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllUser($status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getUserPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function create($input, $member_id);

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
    public function update($input, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    

    public function updateProfile($input, $id);
    public function updateUserPassword($input);

}
