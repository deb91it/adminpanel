<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Dashboard\DashboardRepository;
use DB;

class DashboardController extends Controller
{
    protected $dashboard;

    function __construct(
        DashboardRepository $dashboard
    )
    {
        $this->dashboard = $dashboard;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $statistics = $this->dashboard->getDashboardStatistics();
        return view('admin.dashboard.index')
                    ->withStatistics($statistics);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function lockScreen()
    {
        return view('admin.dashboard.lock-screen');
    }

    public function getOperationStatistics(Request $request)
    {
        $stat =  $this->dashboard->getOperationStatistics($request);
        return response()->json(["success" => $stat->number_of_parcel > 0 ? true : false, "data" => $stat, "msg" => $stat->number_of_parcel > 0 ? "Successfully found." : "No record found"]);
    }

    public function getFinancialStatistics(Request $request)
    {
        $stat =  $this->dashboard->getFinancialStatistics($request);
        return response()->json(["success" => !empty($stat->income) ? true : false, "data" => $stat, "msg" => !empty($stat->income) ? "Successfully found." : "No record found"]);
    }

    public function getCollectionStatistics(Request $request)
    {
        $stat =  $this->dashboard->getCollectionStatistics($request);
        return response()->json(["success" => $stat['total_amount'][0]->total_amount > 0 ? true : false, "data" => $stat, "msg" => !empty($stat->income) ? "Successfully found." : "No record found"]);
    }
}
