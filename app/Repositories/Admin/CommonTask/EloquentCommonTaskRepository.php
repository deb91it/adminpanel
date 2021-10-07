<?php namespace App\Repositories\Admin\CommonTask;


use DB;

class EloquentCommonTaskRepository implements CommonTaskRepository
{

    function __construct()
    {

    }

    /**
     * @param $member_id
     * @return mixed
     */
    public function changeCanLoginStatus($member_id)
    {
        DB::table('members')
            ->where('id', $member_id)
            ->update(['can_login' =>DB::raw("IF (can_login = 0, '1', '0')")]);
        return true;
    }

    /**
     * @param $member_id
     * @return mixed
     */
    public function changeIsActiveStatus($member_id)
    {
        DB::table('members')
            ->where('id', $member_id)
            ->update(['activation_code' => DB::raw("IF (is_active = 0, '', activation_code)"), 'is_active' =>DB::raw("IF (is_active = 0, '1', '0')")]);
        return true;
    }
}
