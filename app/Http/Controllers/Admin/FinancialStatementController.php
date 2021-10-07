<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\FinancialStatement\FinancialStatementRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Milon\Barcode\DNS1D;


class FinancialStatementController extends Controller
{
    protected $report;

    public function __construct(
        FinancialStatementRepository $report
    )
    {
        $this->report = $report;
    }

    public function index(Request  $request)
    {
        $data = $this->searchStatement($request);
        return view('admin.financial_statement.index', $data);
    }

    public function searchStatement(Request  $request)
    {
        $request->from_date = isset($request->from_date) ? $request->from_date : date("Y-m-01");
        $request->to_date = isset($request->to_date) ? $request->to_date : date("Y-m-d");
        $income = $this->report->getIncomeStatement($request);
        $expense = $this->report->getExpenseStatement($request);
        $data = [
            "income" => $income,
            "expense" => $expense,
            "from_date" => $request->from_date,
            "to_date" => $request->to_date,
        ];
        return $data;
    }

    public function barcode()
    {
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-ABCDEFGH", "C39+",1,70) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-10203040", "C39",1,70,array(1,1,1), true) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br><br>';
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-ABC102DE", "C39+",1,70,array(1,1,1)) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-ABC102DE", "C39E",1,70,array(1,1,1)) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br><br>';
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-ABC102DF", "C39E+",1,70,array(1,1,1)) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-00000000", "C93",1,85) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br><br>';
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-CDF10201", "C128",1,70) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
       // echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-FFFF2020", "C128A",1,70,array(1,1,1)) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>';
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-DDDDDDDD", "C128B",1,70,array(1,1,1)) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("parcelbd-DCVF1234", "C128B",1,70,array(1,1,1)) . '" alt="barcode"   /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
}
