<?php namespace App\Repositories\Api\Agent;

use App\Repositories\Common\CommonRepository;

interface AgentRepository extends CommonRepository
{

    /**
     * @param $inputs
     * @return mixed
     */
    public function register($input, $member_id);

    /**
     * @param $member_id
     * @return mixed
     */
    public function getUserDetails($member_id);

    /**
     * @param $member_id
     * @param $merchant_id
     * @return mixed
     */
    public function details($member_id, $merchant_id);
    public function getAgentPaginated($merchant_id, $per_page, $status = 1, $order_by = 'm.id', $sort = 'asc');

}