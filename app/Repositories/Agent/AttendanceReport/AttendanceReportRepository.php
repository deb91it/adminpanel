<?php namespace App\Repositories\Agent\AttendanceReport;

interface AttendanceReportRepository
{
    public function getExportData($inputs);
}