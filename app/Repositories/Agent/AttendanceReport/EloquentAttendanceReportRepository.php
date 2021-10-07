<?php namespace App\Repositories\Agent\AttendanceReport;

use DB;
use Datatables;

class EloquentAttendanceReportRepository implements AttendanceReportRepository
{
    public function getExportData($inputs){
        return $inputs['employee_type'];
    }

}