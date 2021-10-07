<?php namespace App\Repositories\Api\Attendance;

/**
 *  Mamun
 * @package App\Repositories\Driver
 */
interface AttendanceRepository {
    public function getAttendanceList($input, $employee_id);
    public function isAttendanceExist($input);
    public function postStoreAttendance($input);
    public function postUpdateAttendance($input, $id);
    public function postUpdateOnlyAttendanceStatus($input, $id);
    public function storeWithPolicy($input);
}
