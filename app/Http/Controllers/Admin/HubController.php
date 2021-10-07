<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\CourierZones;
use App\DB\Admin\Hub;
use App\DB\Admin\TrackingDetailsSummary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Hub\HubRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\HubRequest;
use App\Http\Requests\Admin\StoreRiderRequest;
use Excel;
use DB;
use PDF;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class HubController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $hub;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * Hub Controller constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        HubRepository $hub
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->hub = $hub;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
    public function index(Request $request)
    {
        return view('admin.hub.index');
    }

    public function getDataTableReport(Request $request){
        return $this->hub->getReportPaginated($request);
    }
    

    public function create(){
        return view('admin.hub.create');
    }

    public function store(HubRequest $requests){
        $hub_id = $this->hub->store($requests);
        if ($hub_id > 0) {
            return redirect('admin/hub')->with('flashMessageSuccess','The Hub has successfully created !');
        }
        return redirect('admin/hub')->with('flashMessageError','Unable to create Hub');
    }
    
    public function edit($id){
        $hub = Hub::find($id);
        return view('admin.hub.edit',compact('hub'));

    }

    public function update(HubRequest $requests, $id ){
        $hub_id = $this->hub->update($requests, $id);
        if ($hub_id > 0) {
            return redirect('admin/hub')->with('flashMessageSuccess','The Hub has successfully updated !');
        }
        return redirect('admin/hub')->with('flashMessageError','Unable to update Hub');
    }

    public function destroy( $id ){
        $hub_id = $this->hub->destroy($id);
        if ($hub_id > 0) {
            return redirect('admin/hub')->with('flashMessageSuccess','The Hub has successfully deleted !');
        }
        return redirect('admin/hub')->with('flashMessageError','Unable to delete the Hub');
    }

}
