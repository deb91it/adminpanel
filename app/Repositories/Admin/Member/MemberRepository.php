<?php namespace App\Repositories\Admin\Member;

interface MemberRepository
{
    /**
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAllmember($status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param $per_page
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getMemberPaginated($per_page, $status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param $input
     * @param $roles
     * @return mixed
     */
    public function create($input, $user_type, $model_id, $role_id);

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
    public function update($input, $id, $roles);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
