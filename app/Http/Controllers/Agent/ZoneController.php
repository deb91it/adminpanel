<?php

namespace App\Http\Controllers\Agent;

use App\DB\Agent\Agent;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\Zones\ZonesRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use Excel;
use DB;
use Validator;
use Illuminate\Support\Facades\Hash;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class ZoneController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $zones;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;
    protected $zones_id;
    protected $company_id;

    /**
     * AgentController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        ZonesRepository $zones
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->zones = $zones;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
        $this->agent_id = getLogedinAgentId();
        $this->company_id = get_agent_company_id();
    }

    public function index(Request $request)
    {
        !empty($request->division) ? $divi_id = $request->division : $divi_id = 3;
        !empty($request->district) ? $dist_id = $request->district : $dist_id = 322;
        $division = DB::table('divisions')->get();
        $zones = DB::table('zones')->select('zone_id','name')->where('division_id',$divi_id)->get();
        $results = $this->zones->searchZones($divi_id,$dist_id);
        return view('agent.zones.index',compact('results','division','zones','dist_id','divi_id'));
    }

    public function getZones(Request $request){
        $zones = DB::table('zones')->select('zone_id','name')->where('division_id',$request->division_id)->get();
        return response()->json(['success'=>true,'result'=>$zones]);
    }

}
