<?php namespace App\Repositories\Api\Member;

use App\Repositories\Common\CommonRepository;

interface MemberRepository {

    /**
     * @param array $ar_filter_params
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc');

    /**
     * @param $id
     * @param int $status
     * @return mixed
     */
    public function getById($id, $status = 1);

    /**
     * @param $id
     * @param $inputs
     * @return mixed
     */
    public function create($input, $user_type, $model_id, $role_id, $activation_code);

    /**
     * @param $id
     * @param $inputs
     * @return mixed
     */
    public function update($id, $inputs);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * @return mixed
     */
    public function getErrors();

    public function storeDeviceID($member_id, $device_id);
}
