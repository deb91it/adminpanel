<?php

namespace App\Http\Controllers\Agent;

use App\DB\Merchant;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\Outlet\OutletRepository;
use DB;
use Response;


class OutletController extends Controller
{
    /**
     * @var
     */
    protected $_errors;


    protected $outlet;
    protected $company_id;


    function __construct(
        OutletRepository $outlet)
    {
        $this->outlet = $outlet;
        $this->company_id = get_agent_company_id();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        return view('agent.outlet.index')
            ->withCategories(DB::table('outlet_categories')->select('id', 'name')->orderBy('id', 'asc')->where('company_id', '=', $this->company_id)->get());
    }

    public function getDataTableReport(Request $request){
        return $this->outlet->getReportPaginated($request, $this->company_id);
    }
}
