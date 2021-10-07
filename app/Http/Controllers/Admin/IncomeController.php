<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\FinancialStatement\FinancialStatementRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class IncomeController extends Controller
{
    protected $report;

    public function __construct(
        FinancialStatementRepository $report
    )
    {
        $this->report = $report;
    }

    public function index()
    {
        $filterStatus = $this->report->getFlagStatus();
        return view('admin.income.index', compact('filterStatus'));
    }

    public function getDataTableReport(Request $request)
    {
        return $this->report->getReportPaginated($request);
    }
}
