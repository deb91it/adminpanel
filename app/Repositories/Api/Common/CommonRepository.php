<?php namespace App\Repositories\Common;

/**
 * Interface CommonRepository
 * @package App\Repositories\Common
 */
interface CommonRepository
{
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
     * @param $inputs
     * @return mixed
     */
    public function create($inputs);

    /**
     * @param $id
     * @param $inputs
     * @return mixed
     */
    public function update($inputs, $id);

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
}
