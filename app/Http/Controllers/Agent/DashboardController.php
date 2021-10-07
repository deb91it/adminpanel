<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\Dashboard\DashboardRepository;
//use App\DB\Agent\AgentMongo;

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
        return view('agent.dashboard.index')
            ->with([
                'data'          => [],
                'sr_reports'    => $this->dashboard->getSrDailyData(),
                'tsr_reports'   => $this->dashboard->getTseDailyData(),
                'total'         => $this->dashboard->getTotalCountedValue(),
                'today'         => $this->dashboard->getTodayCountedValue(),
                'bar_graphs'    => $this->dashboard->getBarGraphData(),
                'remarks'       => $this->dashboard->getDashBoardRemarks()
            ]);
    }

    public function getDashboardBarGraphData(Request $request){
        $this->response['success'] = false;
        $this->response['data'] = '';
        $this->response['message'] = 'Did not found data !';

        $data = $this->dashboard->getDashboardBarGraphData($request);
        if (!empty($data)) {
            $this->response['success'] = true;
            $this->response['data'] = $data;
            $this->response['message'] = 'Data available !';
        }

        return response($this->response, 200);
    }

}
