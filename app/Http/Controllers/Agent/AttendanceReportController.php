<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\AttendanceReport\AttendanceReportRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Agent\Agent\AgentRepository;
use App\DB\Agent\Agent;
use PDF;
use Redirect;

class AttendanceReportController extends Controller
{
    protected $attendance_report;
    protected $roles;
    protected $agent;
    protected $company_id;

    function __construct(
        AttendanceReportRepository $attendance_report,
        RoleRepository $roles,
        AgentRepository $agent
    )
    {
        $this->attendance_report = $attendance_report;
        $this->roles = $roles;
        $this->agent = $agent;
        $this->company_id = get_agent_company_id();
    }

    public function index()
    {
        return view('agent.reports.attendance-report')
            ->withRoles($this->agent->getCompanyRoleList($this->company_id));
    }

    /*public function  getExportData(Request $request){
        return $this->attendance_report->getExportData($request);exit;
            view('agent.reports.attendance-report')
            ->withExportdata($this->attendance_report->getExportData($request));
    }*/

    public function attendanceReportPdfView(){
        return view('agent.reports.attendance-report-pdf');
    }

//    public function getExportData(Request $request){
//        return view('agent.reports.attendance-report-pdf')
//        ->withData($this->attendance_report->getExportData($request));
//    }

    public function getExportData(Request $request){
        $pdf = PDF:: loadView('agent.reports.attendance-report-pdf');
        return $pdf -> download('invoice.pdf');
    }
    public function attendanceReportPdfDownload(){
        $pdf = PDF:: loadView('agent.reports.attendance-report-pdf')->setPaper('legal', 'landscape');
        $pdf->stream();
        return $pdf -> download('invoice.pdf');
    }
}