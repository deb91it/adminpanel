<?php namespace App\Repositories\Admin\CommonTask;

interface CommonTaskRepository
{

    /**
     * @param $member_id
     * @return mixed
     */
    public function changeCanLoginStatus($member_id);

    /**
     * @param $member_id
     * @return mixed
     */
    public function changeIsActiveStatus($member_id);
}
