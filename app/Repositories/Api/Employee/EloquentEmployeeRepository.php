<?php namespace App\Repositories\Api\Employee;

use App\Repositories\Api\Employee\EmployeeRepository;
use App\DB\Api\Employee;
use DB;

class EloquentEmployeeRepository implements EmployeeRepository
{

    protected $employee;

    function __construct(Employee $employee)
    {
        $this->employee = $employee;
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getSrDashboardData($inputs, $sr_id)
    {
        $today = date("Y-m-d");
        $from = $today . " 00:00:00";
        $to = $today . " 23:59:59";
        $response = [];
        $response['outlet_count'] = 0;
        $response['outlet_count'] = 0;
        $response['new_outlet_count'] = 0;
        $response['new_order_count'] = 0;
        $response['visited_count'] = 0;
        $response['meeting_count'] = 0;
        $response['remarks_count'] = 0;
        $response['total_owner_count'] = 0;
        $response['new_owner_count'] = 0;
        $response['check_in_time'] = '00:00';
        $response['check_out_time'] = '00:00';

        $sql = "SELECT
                (SELECT CONCAT(IFNULL(checkedin_time, ''), ',', IFNULL(checkedout_time, '')) FROM sr_attendence WHERE
                        employee_id = a.id
                            AND (attendance_date BETWEEN '{$today}' AND '{$today}') ORDER BY id DESC LIMIT 1) AS check_in_out,
                (SELECT 
                        COALESCE(COUNT(id), 0)
                    FROM
                        outlets
                    WHERE
                        sr_id = a.id) AS outlet_count,
                (SELECT 
                        COALESCE(COUNT(id), 0)
                    FROM
                        outlets
                    WHERE
                        sr_id = a.id
                            AND (created_at BETWEEN '{$from}' AND '{$to}')) AS new_outlet_count,
                            
                (SELECT 
                        COALESCE(COUNT(id), 0)
                    FROM
                        outlet_owners
                    WHERE
                        sr_id = a.id) AS total_owner_count,
                        
                (SELECT 
                        COALESCE(COUNT(id), 0)
                    FROM
                        outlet_owners
                    WHERE
                        sr_id = a.id
                            AND (created_at BETWEEN '{$from}' AND '{$to}')) AS new_owner_count,
                            
                (SELECT 
                        COALESCE(SUM(is_ordered), 0)
                    FROM
                        sr_outlet_order
                    WHERE
                        sr_id = a.id
                            AND (created_at BETWEEN '{$from}' AND '{$to}')) AS new_order_count,
               
                (SELECT 
                        COALESCE(SUM(is_visited), 0)
                    FROM 
                        sr_outlet_order
                    WHERE
                        sr_id = a.id) AS visited_count,
                        
                (SELECT 
                    COALESCE(SUM(CASE WHEN remarks != '' THEN 1 ELSE 0 END), 0) AS remarks_count
                    FROM 
                        sr_outlet_order
                    WHERE
                        sr_id = a.id) AS remarks_count,
                        
                (SELECT 
                        COALESCE(SUM(has_meeting), 0)
                    FROM
                        sr_outlet_order
                    WHERE
                        sr_id = a.id
                            AND (meeting_time BETWEEN '{$from}' AND '{$to}')) AS meeting_count
            FROM
                agents AS a
            WHERE
                a.id = {$sr_id}";
        $rows = collect(DB::select($sql))->first();
        if (empty($rows)) {
            return $response;
        }

        if(!empty($rows->check_in_out)){
            list($check_in, $check_out) = explode(',', $rows->check_in_out);
            $rows->check_in_time = $check_in != '' ? date('h:i A', strtotime($check_in)) : '00:00';
            $rows->check_out_time = $check_out != '' ? date('h:i A', strtotime($check_out)) : '00:00';
            $rows->checkedin_status = true;
        } else {
            $rows->check_in_time = '00:00';
            $rows->check_out_time = '00:00';
            $rows->checkedin_status = false;
        }

        unset($rows->check_in_out);

        return $rows;
    }

    public function getSrMeetingData($request, $sr_id){
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $company_id = getCompanyIdByAgentId($sr_id);
        $rows = DB::table('sr_outlet_order as soo')
            ->select(
                'o.name as outlet_name',
                'o.address as outlet_address',
                'o.image_url as outlet_image_url',
                DB::raw("DATE_FORMAT(soo.meeting_time, '%Y-%m-%d') as meeting_date"),
                DB::raw("DATE_FORMAT(soo.meeting_time, '%h:%i %p') as meeting_time"),
                DB::raw('CONCAT(oow.first_name, " ", oow.last_name) AS owner_name'),
                'oow.phone as owner_phone_no',
                'soo.remarks'
            )
            ->join('outlets as o', 'o.id', '=', 'soo.outlet_id')
            ->join('outlet_owners as oow', 'oow.id', '=', 'o.owner_id')
            ->where([ 'soo.sr_id' => $sr_id, 'soo.company_id' => $company_id, 'soo.has_meeting' => 1 ])
            ->where('soo.meeting_time', '!=', null);
        if ($from_date != '' && $to_date != '') {
            $rows = $rows->whereBetween('soo.meeting_time', [$from_date . ' 00:00:00', $to_date. ' 23:59:59']);
        }
        $rows = $rows->get();

        return $rows;
    }

    public function getSrOutletOwnerList($input, $sr_id){
        return DB::table('outlet_owners as ow')
            ->selectRaw(
                "ow.id as owner_id, ow.first_name, ow.last_name, ow.phone, ow.email, ow.gender, ow.has_outlet,
                IFNULL(ow.profile_pic_url, '') as profile_pic_url,
                IFNULL(o.name, '') as outlet_name, IFNULL(o.address, '') as outlet_address"
            )
            ->leftJoin('outlets as o', 'o.owner_id', '=', 'ow.id')
            ->where('ow.sr_id', $sr_id)
            ->get();
    }

    public function getTseDashboardData($inputs, $tse_id){
        $today = date("Y-m-d");
        $from = $today . " 00:00:00";
        $to = $today . " 23:59:59";
        $sr_ids = [];

        $sr_rows = DB::select("SELECT id FROM agents WHERE parent_id = {$tse_id}");
        if(!empty($sr_rows)){
            foreach ($sr_rows as $val) {
                $sr_ids[] = $val->id;
            }
        }

        $sr_id_string = join(",", $sr_ids);

        // When did not found any sr id, handle exception in this way 
        if($sr_id_string == ''){
            $sr_id_string = '-1';
        }

        $sql = "SELECT
            (SELECT 
                    COUNT(id)
                FROM
                    agents
                WHERE
                    parent_id = {$tse_id}) AS total_no_of_sr,
                (SELECT 
                        COUNT(id)
                    FROM
                        sr_attendence
                    WHERE
                        (created_at BETWEEN '{$from}' AND '{$to}')
                    AND
                        employee_id IN ({$sr_id_string})) AS present,
                        
                (SELECT 
                        COUNT(id)
                    FROM
                        outlets
                    WHERE
                        sr_id IN ({$sr_id_string})) AS total_outlet,
                        
                (SELECT 
                        COUNT(id)
                    FROM
                        outlets
                    WHERE
                        (created_at BETWEEN '{$from}' AND '{$to}')
                    AND 
                      sr_id IN ( {$sr_id_string})) AS today_new_outlet,
                      
                (SELECT 
                        SUM(is_ordered)
                    FROM
                        sr_outlet_order
                    WHERE
                        sr_id IN ({$sr_id_string})) AS total_no_of_order,
                        
                (SELECT 
                        CAST(SUM(ordered_amount) as UNSIGNED)
                    FROM
                        sr_outlet_order
                    WHERE
                        sr_id IN ({$sr_id_string})) AS total_sale_amount, 
                        
                (SELECT CONCAT(IFNULL(checkedin_time, ''), ',', IFNULL(checkedout_time, '')) FROM sr_attendence WHERE
                        employee_id = {$tse_id}
                            AND (attendance_date BETWEEN '{$today}' AND '{$today}') ORDER BY id DESC LIMIT 1) AS check_in_out,
                
                COALESCE(SUM(is_order), 0) AS today_no_of_order,
                COALESCE(SUM(ordered_amount), 0) AS today_sale_amount,
                COALESCE(SUM(is_visited), 0) AS today_visited_count,
                COALESCE(SUM(has_meeting), 0) AS today_meeting_count,
                COALESCE(SUM(remarks_count), 0) AS today_remarks_count
            FROM
                (SELECT 
                    td.*
                FROM
                    agents AS a
                JOIN (SELECT 
                    sr_id AS id,
                    SUM(is_ordered) AS is_order,
                    SUM(is_visited) AS is_visited,
                    SUM(has_meeting) AS has_meeting,
                    SUM(ordered_amount) AS ordered_amount,
                    SUM(CASE WHEN remarks != '' THEN 1 ELSE 0 END) AS remarks_count
                FROM
                    sr_outlet_order
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}')
                GROUP BY sr_id) AS td ON td.id = a.id
                WHERE
                    a.parent_id = {$tse_id}) AS sub_Q";

        $result = collect(DB::select($sql))->first();

        $result->total_no_of_order = (int) $result->total_no_of_order;
        $result->today_no_of_order = (int) $result->today_no_of_order;
        $result->total_sale_amount = (int) $result->total_sale_amount;
        $result->today_sale_amount = (int) $result->today_sale_amount;
        $result->today_visited_count = (int) $result->today_visited_count;
        $result->today_no_of_order = (int) $result->today_no_of_order;
        $result->today_meeting_count = (int) $result->today_meeting_count;
        $result->today_remarks_count = (int) $result->today_remarks_count;

        $response = $result;
        $response->linechart[] = [
            'today_no_of_order' => $result->today_no_of_order,
            'today_sale_amount' => $result->today_sale_amount,
            'date'              => date("Y-m-d")
        ];

        $response->barchart[] = [
            'today_visited_count'  => $result->today_visited_count,
            'today_no_of_order'    => $result->today_no_of_order,
            'today_remarks_count'  => $result->today_remarks_count,
            'date'                 => date("Y-m-d")
        ];

        if($result->total_no_of_sr > 0){
            $present_percent = ($result->present/$result->total_no_of_sr) * 100;
        } else {
            $present_percent = 0;
        }

        $response->piechart = [
            'present'    => round($present_percent, 2),
            'absent'     => round(100-$present_percent,2),
            'date'       => date("Y-m-d")
        ];

        // Attendance
        if(!empty($result->check_in_out)){
            list($check_in, $check_out) = explode(',', $result->check_in_out);
            $response->check_in_time = $check_in != '' ? date('h:i A', strtotime($check_in)) : '00:00';
            $response->check_out_time = $check_out != '' ? date('h:i A', strtotime($check_out)) : '00:00';
            $response->checkedin_status = true;
        } else {
            $response->checkedin_time  = '00:00';
            $response->checkedout_time  = '00:00';
            $response->checkedin_status = false;
        }
        unset($response->check_in_out);
        unset($response->present);

        return $response;
    }

    public function getTseChartData($input, $tse_id, $chart_type){
        $results = [];
        $from_date = $input['from_date'];
        $to_date = $input['to_date'];

        if ($from_date == '') {
            $from_date =  date('Y-m-d');
        }

        if ($to_date == '') {
            $to_date =  date('Y-m-d');
        }

        $dates = getAllDateOfRangePeriod($from_date, $to_date);
        if(empty($dates)){
            return [];
        }
        $from = $dates[0].' 00:00:00';
        $to = $dates[count($dates)-1].' 23:59:59';

        $sr_ids = [];
        $sr_rows = DB::select("SELECT id FROM agents WHERE parent_id = {$tse_id}");
        if(!empty($sr_rows)){
            foreach ($sr_rows as $val) {
                $sr_ids[] = $val->id;
            }
        }

        if($chart_type == 'line'){
            $results = $this->getTseLineChartData($dates, $from, $to, $sr_ids);
        } else if($chart_type == 'bar'){
            $results = $this->getTseBarChartData($dates, $from, $to, $sr_ids);
        }

        return $results;
    }

    private function getTseBarChartData($dates, $from, $to, $sr_ids){
        $results = [];
        $process_outlet = [];
        $process_result = [];

        $sr_id_string = join(",", $sr_ids);

        $sql = "SELECT 
                    COALESCE(SUM(is_ordered), 0) as today_no_of_order,
                    COALESCE(SUM(is_visited), 0) as today_visited_count,
                    CAST(SUM(CASE WHEN remarks != '' THEN 1 ELSE 0 END) as UNSIGNED) AS today_remarks_count,
                    DATE_FORMAT(created_at, '%Y-%m-%d') as date_month
                FROM
                    sr_outlet_order
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}')
                      AND sr_id IN ({$sr_id_string})
                GROUP BY CAST(created_at AS DATE)";
        $rows = DB::select($sql);

        /*$outlet_sql = "SELECT
                    COUNT(id) as new_outlet,
                     DATE_FORMAT(created_at, '%Y-%m-%d') as date_month
                FROM
                    outlets
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}')
                      AND sr_id IN ({$sr_id_string})
                GROUP BY CAST(created_at AS DATE)";
        $new_outlet = DB::select($outlet_sql);*/

        if (!empty($rows)) {
            foreach ($rows as $val) {
                $process_result[$val->date_month] = [
                    'today_no_of_order' => (int)$val->today_no_of_order,
                    'today_visited_count' => (int)$val->today_visited_count,
                    'today_remarks_count' => (int)$val->today_remarks_count
                ];
            }
        }

        /*if(!empty($new_outlet)){
            foreach ($new_outlet as $v) {
                $process_outlet[$v->date_month] = $v->new_outlet;
            }
        }*/

        foreach ($dates as $key => $date) {
            $single_arr = [];
            if (array_key_exists($date, $process_result)) {
                $single_arr['today_no_of_order'] = $process_result[$date]['today_no_of_order'];
                $single_arr['today_visited_count'] = $process_result[$date]['today_visited_count'];
                $single_arr['today_remarks_count'] = $process_result[$date]['today_remarks_count'];
                $single_arr['date'] = $date;
            } else {
                $single_arr['today_no_of_order'] = 0;
                $single_arr['today_visited_count'] = 0;
                $single_arr['today_remarks_count'] = 0;
                $single_arr['date'] = $date;
            }

            /*if (array_key_exists($date, $process_outlet)) {
                $single_arr['new_outlet'] = $process_outlet[$date];
                $single_arr['date'] = $date;
            } else {
                $single_arr['new_outlet'] = 0;
                $single_arr['date'] = $date;
            }*/

            $results[] = $single_arr;
        }

        return  $results;
    }

    private function getTseLineChartData($dates, $from, $to, $sr_ids){
        $results = [];
        $process_result = [];
        $process_outlet = [];
        $sr_id_string = join(",", $sr_ids);

        $sql = "SELECT 
                    COALESCE(SUM(is_ordered), 0) as today_no_of_order,
                    COALESCE(SUM(ordered_amount), 0) as today_sale_amount,
                    DATE_FORMAT(created_at, '%Y-%m-%d') as date_month
                FROM
                    sr_outlet_order
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}')
                      AND sr_id IN ({$sr_id_string})
                GROUP BY CAST(created_at AS DATE)";
        $rows = DB::select($sql);

        /*$outlet_sql = "SELECT
                    COUNT(id) as new_outlet,
                     DATE_FORMAT(created_at, '%Y-%m-%d') as date_month
                FROM
                    outlets
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}')
                      AND sr_id IN ({$sr_id_string})
                GROUP BY CAST(created_at AS DATE)";
        $new_outlet = DB::select($outlet_sql);*/

        if (!empty($rows)) {
            foreach ($rows as $val) {
                $process_result[$val->date_month] = [
                    'today_no_of_order' => (int)$val->today_no_of_order,
                    'today_sale_amount' => (int)$val->today_sale_amount,
                ];
            }
        }

        /*if (!empty($new_outlet)) {
            foreach ($new_outlet as $v) {
                $process_outlet[$v->date_month] = $v->new_outlet;
            }
        }*/

        foreach ($dates as $key => $date) {
            $single_arr = [];
            if (array_key_exists($date, $process_result)) {
                $single_arr['today_no_of_order'] = $process_result[$date]['today_no_of_order'];
                $single_arr['today_sale_amount'] = $process_result[$date]['today_sale_amount'];
                $single_arr['date'] = $date;
            } else {
                $single_arr['today_no_of_order'] = 0;
                $single_arr['today_sale_amount'] = 0;
                $single_arr['date'] = $date;
            }

            /*if (array_key_exists($date, $process_outlet)) {
                $single_arr['new_outlet'] = $process_outlet[$date];
                $single_arr['date'] = $date;
            } else {
                $single_arr['new_outlet'] = 0;
                $single_arr['date'] = $date;
            }*/

            $results[] = $single_arr;
        }

        return  $results;
    }

    public function getSrListOfTse($inputs, $tse_id) {
        $from =  date("Y-m-d")." 00:00:00";
        $to =  date("Y-m-d")." 23:59:59";
        $sql ="SELECT 
                a.id,
                a.first_name,
                a.last_name,
                m.mobile_no,
                m.email,
                CAST(a.gender as UNSIGNED) as gender,
                IFNULL(a.date_of_birth, '') as date_of_birth,
                a.profile_pic,
                a.profile_pic_url,
                a.pic_mime_type,
                (SELECT 
                    GROUP_CONCAT(
                        CONCAT(
                            'checkedin_time~', IFNULL(checkedin_time, ''),
                            ',', 
                            'checkedout_time~', IFNULL(checkedout_time, ''),
                            ',', 
                            'status~', status
                        )
                     SEPARATOR ', ')
                    FROM sr_attendence 
                    WHERE 
                    employee_id = a.id
                        AND (attendance_date BETWEEN '{$from}' AND '{$to}')) as attendance
            FROM
                agents AS a
            JOIN members as m ON m.id = a.member_id
            WHERE
                a.parent_id = {$tse_id}";

        $rows = DB::Select($sql);

        if (empty($rows)) {
            return [];
        }

        foreach ($rows as $row) {
            $attendance =  [
                'checkedin_time'  => '00:00',
                'checkedout_time' => '00:00',
                'status'          => '0'
            ];

            if(!empty($row->attendance)) {
                $attendance = [];
                foreach (explode(',', $row->attendance) as $v) {
                    $item = explode('~', $v);
                    if($item[0] == 'checkedin_time'){
                        $attendance[$item[0]] = $item[1] == '' ? '00:00' : date('h:i A', strtotime($item[1]));
                    }

                    if($item[0] == 'checkedout_time'){
                        $attendance[$item[0]] = $item[1] == '' ? '00:00' :  date('h:i A', strtotime($item[1]));
                    }

                    if($item[0] == 'status'){
                        $attendance[$item[0]] = $item[1];
                    }
                }
            }

            $row->attendance = $attendance;
            $row->details = $this->getSrDetailsData($row->id);
        }
        return $rows;
    }

    private function getSrDetailsData($sr_id){
        $from =  date("Y-m-d")." 00:00:00";
        $to =  date("Y-m-d")." 23:59:59";

        $sql = "SELECT                             
                    (SELECT COUNT(id) FROM outlets where sr_id = {$sr_id}) as total_outlet,
                    (SELECT COUNT(id) FROM outlets where sr_id = {$sr_id} AND (created_at BETWEEN '{$from}' AND '{$to}')) as today_new_outlet,
                    (SELECT SUM(is_ordered) FROM sr_outlet_order where sr_id = {$sr_id}) as total_no_of_order,
                    COALESCE(SUM(is_ordered), 0) as today_no_of_order,
                    (SELECT SUM(ordered_amount) FROM sr_outlet_order where sr_id = {$sr_id}) as total_sale_amount,
                    COALESCE(SUM(ordered_amount), 0) as today_sale_amount,
                    (SELECT SUM(is_visited) FROM sr_outlet_order where sr_id = {$sr_id}) as total_visited_count,
                    COALESCE(SUM(is_visited), 0) as today_visited_count,                  
                    (SELECT 
                            COALESCE(SUM(is_visited), 0)
                        FROM
                            sr_outlet_order
                        WHERE
                            sr_id = {$sr_id}) AS total_visited_count,                            
                    COALESCE(SUM(CASE WHEN remarks != '' THEN 1 ELSE 0 END), 0) AS today_remarks_count  
                FROM
                    sr_outlet_order
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}')
                      AND sr_id = {$sr_id}";
        $details = collect(DB::select($sql))->first();
        if(!empty($details)){
            array_walk($details, function(&$v){ $v = intval($v); });
        }

        return $details;
    }

    public function explodeArraylistInArray($inputs, $index){
        $listArray = [];
        foreach ($inputs as $item) {
            $listArray[] =$item->$index;
        }
        return (array) $listArray;
    }

    public function update($id, $inputs)
    {
        $merchant                   = $this->merchant->find($id);
        $merchant_member_id         = $merchant->member_id;
        $merchant->first_name       = $inputs['first_name'];
        $merchant->last_name        = $inputs['last_name'];
        if ($merchant->save()) {

            if($inputs['mobile_no'] != "" && $inputs['mobile_no'] != 0)
            {
                $member             = Member::find($merchant_member_id);    
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
}
