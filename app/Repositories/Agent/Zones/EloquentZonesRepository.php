<?php namespace App\Repositories\Agent\Zones;
use App\DB\Admin\Agent;
use App\DB\Admin\Member;
use App\DB\Agent\EmployeeDetails;
use App\DB\Permission;
use DB;
use Datatables;
use App\DB\Admin\Role;


class EloquentZonesRepository implements ZonesRepository
{
    protected $agent;
    protected $role;
    protected $company_id;

    function __construct(Agent $agent, Role $role)
    {
        $this->agent = $agent;
        $this->role = $role;
        $this->company_id = get_agent_company_id();
    }

    public function searchZones($divi_id,$dist_id){
        return DB::table('zones as zn')
            ->select('zn.name as zone','divi.name as division','upa.name as upazilla','coun.name as country')
            ->join('divisions as divi', 'divi.id','=','zn.division_id')
            ->join('upazilas as upa', 'zn.zone_id','=','upa.district_id')
            ->join('countries as coun', 'coun.country_id','=','zn.country_id')
            ->where(['division_id'=>$divi_id,'zone_id'=>$dist_id])
            ->paginate(20);
    }

}
