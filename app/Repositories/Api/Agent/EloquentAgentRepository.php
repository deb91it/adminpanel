<?php namespace App\Repositories\Api\Agent;

use App\DB\Api\Agent;
use App\DB\Driver;
use App\DB\Member;
use App\DB\DeviceRegistration;
use DB;

/**
 * Class EloquentZonesRepository
 * @package App\Repositories\Agent
 */
class EloquentAgentRepository implements AgentRepository
{

    /**
     * @var Agent
     */
    protected $agent;

    /**
     * EloquentZonesRepository constructor.
     * @param Agent $agent
     */
    function __construct(Agent $agent)
    {
        $this->agent = $agent;
        date_default_timezone_set('Asia/Dhaka');
    }

    /**
     * @param array $ar_filter_params
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getAll() method.
    }

    /**
     * @param $id
     * @param int $status
     * @return mixed
     */
    public function getById($id, $status = 1)
    {
        // TODO: Implement getById() method.
    }

    /**
     * @param $inputs
     * @return mixed
     */
    public function create($inputs)
    {
        // TODO: Implement create() method.
    }

    /** Territory Sales Executive Dashboard */
    public function Dashboard($inputs){
        $data = [];
        $total_outlet = 0;
        $visited_outlet = 0;
        $new_outlet = 0;
        $number_outlet = 0;
        $order_amount = 0;
        $no_of_order = 0;

        $sr =  DB::table('drivers as sr')
            ->where([
                'agent_id' => $inputs['tse_id']
            ])
            ->select('id')
            ->get();

        $query =  DB::select(DB::raw('SELECT sr_order.*,sr.id,DATE(sr.created_at) AS outletcreate,DATE (sr_order.created_at) AS outlet_created_date FROM ezzyradmin.vehicles as sr
                  Left join ezzyradmin.sr_outlet_order as sr_order On sr.id = sr_order.outlet_id  WHERE  sr.agent_id='.$inputs['tse_id'].'  Order by sr.id DESC;'));


        if (!empty($query)){
            foreach ($query as $key => $value){

                if (!empty($value)){
                    $total_outlet += 1;
                }

                if ($value->is_visited == 1 && $value->outlet_created_date == date("Y-m-d") ){
                    $visited_outlet +=1;
                }
                if ($value->outletcreate == date("Y-m-d")){
                    $new_outlet +=1;
                }
                if ($value->is_ordered == 1 && $value->outlet_created_date == date("Y-m-d")){
                    $number_outlet +=1;
                }
                if ($value->is_ordered == 1 && !empty($value->ordered_amount) && $value->outlet_created_date == date("Y-m-d")){

                    $order_amount +=$value->ordered_amount;
                }
                if ($value->is_ordered == 1 && !empty($value->ordered_amount) && $value->outlet_created_date == date("Y-m-d")){

                    $no_of_order +=1;
                }
            }
        }

        $today = date('Y-m-d');
        $attendacne = DB::select("SELECT 
                COUNT(id) AS no_of_sr,
                (SELECT 
                        COUNT(sr_id)
                    FROM
                        sr_attendence
                    WHERE
                        (attendance_date BETWEEN '{$today}' AND '{$today}' ) 
                ) AS no_of_present            
            FROM
              drivers 
            where agent_id = {$inputs['tse_id']}");

            $present=0;
            $absent=0;
            //return $attendacne;exit();
            if (!empty($attendacne)) {
                foreach ($attendacne as $percentage) {
                    if ($percentage->no_of_sr != 0) {
                        $present = ($percentage->no_of_present * 100) / $percentage->no_of_sr;
                        $absent = (($percentage->no_of_sr - $percentage->no_of_present) * 100) / $percentage->no_of_sr;
                    }
                }
            }
        $piechart = [
            'present'=>$present,
            'absent'=>$absent,
        ];

        $linechart []= [
            'new_outlet' => $new_outlet,
            'no_of_order' => $no_of_order,
            'visited_outlet' => $visited_outlet,
            'date' => $today,
        ];
        $barchart []= [
            'total_outlet' =>$total_outlet,
            'total_sale_amount' => $order_amount,
            'date' => $today,
        ];

        $data['total_sr'] =count($sr);
        $data['total_outlet'] = $total_outlet;
        $data['visited_outlet'] = $visited_outlet;
        $data['new_outlet'] = $new_outlet;
        $data['number_outlet'] = $number_outlet;
        $data['total_sale_amount'] =$order_amount;
        $data['no_of_order'] =$no_of_order;
        $data['linechart'] =$linechart;
        $data['barchart'] =$barchart;
        $data['piechart'] =$piechart;
        return $data;

    }

    public function lineChartFilter($inputs){

        if (!empty($inputs['fromDate']) && !empty($inputs['toDate'])){
            $new_outlet=DB::table('vehicles')
                ->where('agent_id',$inputs['tse_id'])
                ->whereBetween('created_at',[$inputs['fromDate'],$inputs['toDate']])
                ->count();
//            $no_of_order = DB::table('')

        }
    }
    public function listOfSR($inputs){
        $response = [];
        $listSr = DB::Select("SELECT sr.id,sr.first_name,sr.last_name,mem.mobile_no 
                    FROM drivers as sr
                    JOIN members as mem on sr.member_id = mem.id
                    Where sr.agent_id =".$inputs['tse_id']);


        if (!empty($listSr)){
            foreach ($listSr as $sr){
                $todayVisited = DB::table('sr_outlet_order')
                    ->where(['sr_id'=>$sr->id,'is_visited'=>1])
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 11:59:59')])
                    ->count();
                $todayOrdered = DB::table('sr_outlet_order')
                    ->where(['sr_id'=>$sr->id,'is_visited'=>1,'is_ordered'=>1])
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 11:59:59')])
                    ->count();
                $attendance = DB::table('sr_attendence')
                    ->select('checkedin_time', 'checkedout_time', 'status')
                    ->where(['sr_id'=>$sr->id])
                    ->whereBetween('attendance_date',[date('Y-m-d'),date('Y-m-d')])
                    ->first();
                $sr->todayVisited = $todayVisited;
                $sr->todayOrdered = $todayOrdered;
                if (!empty($attendance)){
                    $attendance = [
                        'checkedin_time' => date('h:i A',strtotime($attendance->checkedin_time)),
                        'checkedout_time' => date('h:i A',strtotime($attendance->checkedout_time)),
                        'status' => $attendance->status,
                    ];
                }else{
                    $attendance = [
                        'checkedin_time' => '',
                        'checkedout_time' => '',
                        'status' => 0,
                    ];
                }
                $sr->attendance = $attendance;
                $response['data'][]=$sr;
            }
        }
        return $response;

    }

    public function explodeArraylistInArray($inputs,$index){
        $listArray = [];
        foreach ($inputs as $item) {
            $listArray[] =$item->$index;
        }
        return $listArray;
    }
    /**
     * @param $id
     * @param $inputs
     * @return mixed
     */
    public function update($id, $inputs)
    {
        $agent                   = $this->agent->find($id);

        $agent_member_id         = $agent->member_id;
        $agent->first_name       = $inputs['first_name'];
        $agent->last_name        = $inputs['last_name'];
        if ($agent->save()) {

            if($inputs['mobile_no'] != "" && $inputs['mobile_no'] != 0)
            {
                $member             = Member::find($agent_member_id);    
                $member->mobile_no  = $inputs['mobile_no'];
                if ($member->save()) {
                    return $id;
                }

                return $id;
            }

            return $id;
        }
        return $id;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }

    /**
     * @param $inputs
     * @return mixed
     */
    public function register($input, $member_id)
    {

        $agent = Agent::where('member_id', $member_id)->first();
        if (empty($agent)) {
            $agent = new Agent();
        }
        $agent->first_name  = $input['first_name'];
        $agent->last_name   = $input['last_name'];
       // $agent->gender      = $input['gender'];
        $agent->language_id = $input['language'];
        $agent->zone_id     = $input['zone_id'];
       // $agent->promo_code  = isset($input['promo_code']) ? $input['promo_code'] : '';
        $agent->member_id   = $member_id;
        if ($agent->save()) {
            return $agent->id;
        }
        return 0;
    }

    public function registerAgentByDriverInfo($agentMember_id, $driver_id)
    {

        $hasAgentId = $this->checkAgentWithMemberId($agentMember_id);

        if($hasAgentId == 0)
        {
            $driverDataAsAgent = Driver::where('id', $driver_id)->first();

            if (empty($driverDataAsAgent)) {
                return false;
            }

            $agent = new Agent();

            $agent->first_name           = $driverDataAsAgent['first_name'];
            $agent->last_name            = $driverDataAsAgent['last_name'];
            $agent->gender               = $driverDataAsAgent['gender'];
            $agent->profile_pic          = $driverDataAsAgent['profile_pic'];
            $agent->pic_mime_type        = $driverDataAsAgent['pic_mime_type'];
            $agent->language_id          = $driverDataAsAgent['language_id'];
            $agent->zone_id              = $driverDataAsAgent['zone_id'];
            $agent->member_id            = $agentMember_id;

            if ($agent->save()) {
                return $agent->id;
            }
            return 0;
        }

        return $hasAgentId;        
    }

    public function registerAgentByMinInfo($agentMember_id, $input)
    {
        
        $driverDataAsAgent = Driver::where('id', $input['driver_id'])->first();
        
        $agent = new Agent();

        $agent->first_name           = $input['first_name'];
        $agent->last_name            = $input['last_name'];
        $agent->gender               = 1;
        $agent->profile_pic          = '';
        $agent->pic_mime_type        = '';
        $agent->language_id          = $driverDataAsAgent['language_id'];
        $agent->zone_id              = $driverDataAsAgent['zone_id'];
        $agent->member_id            = $agentMember_id;

        if ($agent->save()) {
            return $agent->id;
        }
        return 0;
    }

    /**
     * @param $member_id
     * @return mixed
     */
    public function getUserDetails($member_id)
    {
        return $this->agent->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    /**
     * @param $member_id
     * @param $agent_id
     * @return mixed
     */
    public function details($member_id, $agent_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getAgentPaginated($agent_id, $per_page, $status = 1, $order_by = 'm.id', $sort = 'asc')
    {
        return DB::table('members as m')
            ->select(
                'd.first_name','d.last_name','m.id as member_id','mc.first_name as agent_first_name',
                'mc.last_name as agent_last_name'
            )
            ->join('drivers as d', 'd.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'd.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->join('agents as mc', 'mc.id', '=', 'd.agent_id')
            ->where('m.user_type', 2)
            ->where('d.status', $status)
            ->where('d.agent_id', $agent_id)
            ->orderBy($order_by, $sort)
            ->get();
            //->paginate($per_page);
    }

    protected function checkAgentWithMemberId($member_id){
        

        $agentData = Agent::where('member_id', $member_id)->first();
        
        if (!empty($agentData) && $agentData['id'] > 0) {
            return $agentData['id'];
        }

        return 0;

    }
}
