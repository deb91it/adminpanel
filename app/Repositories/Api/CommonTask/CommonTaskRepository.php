<?php namespace App\Repositories\Api\CommonTask;

interface CommonTaskRepository
{
    public function getEmployeeData($user_id, $user_type);
    public function checkCurrentAttendance($employee_id);
    public function postUploadProfileImage($request);
    public function updateMerchantProfile($request);

}
