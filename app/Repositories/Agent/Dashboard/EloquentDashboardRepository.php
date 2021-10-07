<?php namespace App\Repositories\Agent\Dashboard;


use DB;
use File;

class EloquentDashboardRepository implements DashboardRepository
{
    protected $company_id;
    protected $MDB;

    function __construct()
    {
        $this->company_id = get_agent_company_id();
    }

    public function getDashboardData()
    {


    }

    public function getTseDailyData($from = '', $to = '')
    {
        $depth = getCompanySettings($this->company_id)->max_depth;
        if($depth == '' || $depth < 1){
            $depth = 5;
        }

        if ($from == '' || $to == '') {
            $from = date("Y-m-d") . " 00:00:00";
            $to = date("Y-m-d") . " 23:59:59";
        }

        $sql = "SELECT 
                CONCAT(a.first_name, ' ', a.last_name) AS full_name,
                a.designation,
                a.profile_pic,
                a.profile_pic_url,
                COALESCE(srr.order_amount, 0) AS order_amount,
                COALESCE(srr.visited_count, 0) AS visited_count,
                (SELECT COUNT(id) FROM agents WHERE parent_id = a.id) as sr_count
            FROM
                (SELECT 
                    agn.parent_id, sr.order_amount, sr.visited_count
                FROM
                    agents AS agn
                LEFT JOIN (SELECT 
                    sr_id,
                        SUM(ordered_amount) AS order_amount,
                        SUM(is_visited) AS visited_count
                FROM
                    sr_outlet_order
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}')
                      AND  company_id = {$this->company_id}
                GROUP BY sr_id) AS sr ON sr.sr_id = agn.id
                WHERE
                    agn.depth = {$depth}) AS srr
                    RIGHT JOIN
                agents AS a ON a.id = srr.parent_id
            WHERE
                ( a.depth = {$depth} -1 ) AND a.company_id = {$this->company_id}
            GROUP BY srr.parent_id";

        return DB::select($sql);
    }

    public function getSrDailyData($from = '', $to = '')
    {
        $depth = getCompanySettings($this->company_id)->max_depth;
        if($depth == '' || $depth < 1){
            $depth = 5;
        }

        if ($from == '' || $to == '') {
            $from =  date("Y-m-d")." 00:00:00";
            $to =  date("Y-m-d")." 23:59:59";
        }

        $sql = "SELECT 
                    agn.id,
                    CONCAT(agn.first_name, ' ', agn.last_name) AS full_name,
                    agn.designation,
                    agn.profile_pic,
                    agn.profile_pic_url,
                    COALESCE(sr.order_amount, 0) AS order_amount,
                    COALESCE(sr.visited_count, 0) AS visited_count
                FROM
                    agents AS agn
                        LEFT JOIN
                    (SELECT 
                        sr_id, 
                        SUM(ordered_amount) AS order_amount,
                        SUM(is_visited) as visited_count
                    FROM
                        sr_outlet_order
                    WHERE
                        (created_at BETWEEN '{$from}' AND '{$to}')
                      AND  company_id = {$this->company_id}
                    GROUP BY sr_id) AS sr ON sr.sr_id = agn.id
                WHERE
                    agn.depth = {$depth} AND agn.company_id = {$this->company_id}" ;

        return DB::select($sql);

    }

    public function getTotalCountedValue()
    {
        $depth = getCompanySettings($this->company_id)->max_depth;
        if($depth == '' || $depth < 1){
            $depth = 5;
        }

        $sql = "SELECT 
                    (SELECT COALESCE(COUNT(id), 0) FROM outlets WHERE company_id = {$this->company_id}) as outlet,
                    COALESCE(SUM(ordered_amount), 0) AS order_amount,
                    (SELECT 
                            COUNT(id)
                        FROM
                            agents
                        WHERE company_id = {$this->company_id} AND
                            depth IN ($depth-1 , $depth)
                    ) AS employee,
                    (SELECT 
                            COUNT(id)
                        FROM
                            outlet_owners
                        WHERE sr_id IN (SELECT id FROM agents WHERE depth = {$depth} AND {$this->company_id})
                    ) AS outlet_contact
                FROM
                    sr_outlet_order
                WHERE
                    company_id = {$this->company_id}";

        return collect(DB::select($sql))->first();
    }

    public function getTodayCountedValue($from = '', $to = '')
    {
        $depth = getCompanySettings($this->company_id)->max_depth;
        if($depth == '' || $depth < 1){
            $depth = 5;
        }

        if ($from == '' || $to == '') {
            $from =  date("Y-m-d")." 00:00:00";
            $to =  date("Y-m-d")." 23:59:59";
        }

        $sql = "SELECT 
                    COALESCE(SUM(is_visited), 0) AS visited,
                    COALESCE(SUM(ordered_amount), 0) AS order_amount,
                    (SELECT 
                            COALESCE(SUM(has_meeting), 0)
                        FROM
                            sr_outlet_order
                        WHERE company_id = {$this->company_id} AND
                            (meeting_time BETWEEN '{$from}' AND '{$to}')) AS meeting,
                    (SELECT 
                            COALESCE(COUNT(employee_id), 0)
                        FROM
                            sr_attendence
                        WHERE company_id = {$this->company_id} AND
                            (attendance_date BETWEEN '{$from}' AND '{$to}')) AS present
                FROM
                    sr_outlet_order
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}') AND company_id = {$this->company_id}";

        return collect(DB::select($sql))->first();
    }

    public function getBarGraphData($from = '', $to = '')
    {
        $results = [];
        $process_result = [];
        if ($from == '' || $to == '') {
            $from =  date('Y-m-d', strtotime("-9 days"))." 00:00:00";
            $to =  date('Y-m-d')." 23:59:59";
        }
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m-%d') AS date_month,
                    COALESCE(SUM(ordered_amount), 0) AS total_order
                FROM
                    sr_outlet_order
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}')
                      AND company_id = {$this->company_id} 
                GROUP BY CAST(created_at AS DATE)";

        $rows = DB::select($sql);
        if (!empty($rows)) {
            foreach ($rows as $val) {
                $process_result[$val->date_month] = $val->total_order;
            }
        }

        $month_dates = getAllDateOfRangePeriod($from, $to);

        foreach ($month_dates as $key => $date) {
            $single_arr = [];
            if (array_key_exists($date, $process_result)) {
                //$results[$key][$date] = $process_result[$date];
                $single_arr['date'] = date('d-m-Y', strtotime($date));
                $single_arr['order'] = $process_result[$date];
            } else {
                $single_arr['date'] = date('d-m-Y', strtotime($date));
                $single_arr['order'] = 0;
            }
            $results[] = $single_arr;
        }

        return $results;
    }

    public function getDashBoardRemarks($from = '', $to = '')
    {
        return DB::table('sr_outlet_order as soo')
            ->select(
                'soo.remarks',
                DB::raw("DATE_FORMAT(soo.created_at,'%d %b, %Y') AS date_time"),
                'o.image as outlet_image',
                'o.image_url as outlet_image_url',
                'o.name as outlet_name',
                DB::raw('CONCAT(ag.first_name, " ", ag.last_name) AS sr_name')
            )
            ->join('outlets as o', 'o.id', '=', 'soo.outlet_id')
            ->join('agents as ag', 'ag.id', '=', 'soo.sr_id')
            ->where('soo.company_id', $this->company_id)
            ->where('soo.remarks', '!=', '')
            ->orderBy('soo.id', 'asc')
            ->paginate(10);
    }

    public function getDashboardBarGraphData($request)
    {
        $search_for = $request->get('search_for');
        $results = [];
        $process_result = [];
        $from = $request->get('from');
        $to = $request->get('to');
        if ($from == '' || $to == '') {
            $from =  date('Y-m-d', strtotime("-14 days"))." 00:00:00";
            $to =  date('Y-m-d')." 23:59:59";
        }

        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m-%d') AS date_month,
                    COALESCE(SUM(ordered_weight), 0) AS order_quantity,
                    COALESCE(SUM(ordered_amount), 0) AS order_amount,
                    COALESCE(SUM(is_visited), 0) AS visited
                FROM
                    sr_outlet_order
                WHERE
                    (created_at BETWEEN '{$from}' AND '{$to}')
                      AND company_id = {$this->company_id} 
                GROUP BY CAST(created_at AS DATE)";
        $rows = DB::select($sql);

        if (!empty($rows)) {
            foreach ($rows as $val) {
                $process_result[$val->date_month] = (int) $val->{$search_for};
            }
        }

        $month_dates = getAllDateOfRangePeriod($from, $to);
        foreach ($month_dates as $key => $date) {
            $single_arr = [];
            if (array_key_exists($date, $process_result)) {
                $single_arr['date'] = date('d-m-Y', strtotime($date));
                $single_arr['val'] = $process_result[$date];
            } else {
                $single_arr['date'] = date('d-m-Y', strtotime($date));
                $single_arr['val'] = 0;
            }
            $results[] = $single_arr;
        }

        return $results;
    }
}
