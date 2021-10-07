<?php namespace App\Repositories\Api\Attendance;

use App\DB\Api\Attendance;
use App\Repositories\Api\Attendance\AttendanceRepository;
use DB;


class EloquentAttendanceRepository implements AttendanceRepository
{
    protected $attendance;

    function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
        date_default_timezone_set('Asia/Dhaka');
    }

    public function getAttendanceList($inputs, $employee_id){
        $data = [];
        $query =  DB::table('sr_attendence')
            ->where('employee_id', $employee_id)
            ->select('*')
            ->whereBetween('attendance_date', [ $inputs['fromDate'], $inputs['toDate'] ])
            ->orderBy('id', 'desc')
            ->get();

        if (!empty($query)) {
            foreach ($query as $key => $value) {
                $data['duration'][] = getTimeDifference($value->checkedin_time, $value->checkedout_time)." Hours";
                $data['attendance'][] = $value->flag == '' ? 'A': $value->flag;
                $value->duration = getTimeDifference($value->checkedin_time, $value->checkedout_time)." Hours";
                $value->attendance = $value->flag == '' ? 'A': $value->flag;
                $value->checkedin_time = $value->checkedin_time == '00:00:00' ? '00:00' : date('h:i A', strtotime($value->checkedin_time));
                $value->checkedout_time = $value->checkedout_time == '' ? '00:00' : date('h:i A', strtotime($value->checkedout_time));
                $data['data'][] = $value;
            }
        }else{
            $data='';
        }
        return $data;
    }

    public function postStoreAttendance($inputs)
    {
        $data = [];
        list($lat, $lng) = explode(",",$inputs['coordinate']);
        $oOrder = new Attendance();
        $oOrder->employee_id       = isset($inputs['employee_id']) ? $inputs['employee_id'] : 0;
        $oOrder->company_id        = $inputs['company_id'];
        $oOrder->attendance_date   = date("Y-m-d");
        $oOrder->checkedin_time        = date('H:i:s');
        $oOrder->checkedin_latitude    = $lat;
        $oOrder->checkedin_longitude   = $lng;
        $oOrder->status        = $inputs['is_online'];
        $oOrder->is_holiday    = 0;
        $oOrder->created_at    = date("Y-m-d H:i:s");
        $data['checked_in'] = date("h:i A",strtotime($oOrder->checkedin_time));
        $data['checked_out'] = "00:00";
        if ($oOrder->save()) {
            return $data;
        }
        return 0;
    }

    public function postUpdateAttendance($inputs,$id)
    {
        list($lat, $lng) = explode(",", $inputs['coordinate']);
        $oOrder = Attendance::find($id);
        $oOrder->checkedout_time        = date('H:i:s');
        $oOrder->checkedout_latitude    = $lat;
        $oOrder->checkedout_longitude   = $lng;
        $oOrder->status        = $inputs['is_online'];
        $oOrder->updated_at    = date("Y-m-d H:i:s");
        $data['checked_in']  = date("h:i A",strtotime($oOrder->checkedin_time));
        $data['checked_out'] = date("h:i A",strtotime($oOrder->checkedout_time));
        if ($oOrder->save()) {
            return $data;
        }
        return 0;
    }

    public function postUpdateOnlyAttendanceStatus($inputs, $id)
    {
        list($lat, $lng) = explode(",", $inputs['coordinate']);
        $oOrder = Attendance::find($id);
        $oOrder->checkedout_latitude    = $lat;
        $oOrder->checkedout_longitude   = $lng;
        $oOrder->status        = $inputs['is_online'];
        if ($oOrder->save()) {
            return $id;
        }
        return 0;
    }

    public function isAttendanceExist($inputs)
    {
        return $outletOrder = DB::table('sr_attendence')->where(
            [   'employee_id'      => $inputs['employee_id'],
                'company_id'       => $inputs['company_id'],
                'attendance_date'  => date('Y-m-d')
            ]
        )->first();
    }

    public function storeWithPolicy($inputs)
    {
        $data = [];
        $flag = 'P';
        $is_holiday = 0;
        $setting_id = 0;
        $this->checkingAttendancePolicy($inputs['company_id'], $flag, $is_holiday);
        $this->checkingHolidaySetup($inputs['company_id'], $flag, $is_holiday, $setting_id);
        list($lat, $lng) = explode(",", $inputs['coordinate']);
        $oOrder = new Attendance();
        $oOrder->employee_id       = isset($inputs['employee_id']) ? $inputs['employee_id'] : 0;
        $oOrder->company_id        = $inputs['company_id'];
        $oOrder->attendance_date   = date("Y-m-d");
        $oOrder->checkedin_time        = date('H:i:s');
        $oOrder->checkedin_latitude    = $lat;
        $oOrder->checkedin_longitude   = $lng;
        $oOrder->status        = $inputs['is_online'];
        $oOrder->flag          = $flag;
        $oOrder->is_holiday    = $is_holiday;
        $oOrder->holiday_setting_id = $setting_id;
        $oOrder->created_at    = date("Y-m-d H:i:s");
        $data['checked_in'] = date("h:i A",strtotime($oOrder->checkedin_time));
        $data['checked_out'] = "00:00";
        if ($oOrder->save()) {
            return $data;
        }
        return 0;
    }

    private function checkingAttendancePolicy($company_id, &$flag, &$is_holiday){
        $time_diff = 0;
        $policy = $this->getAttendancePolicy($company_id);

        if(!empty($policy)) {
            $in_time = date('H:i:s', strtotime($policy->in_time));
            if($in_time < date('H:i:s')){
                $time_diff = getTimeDifferenceInMinute($policy->in_time, date('H:i:s'));
            }

            if($policy->working_type == 0) {
                $flag = 'W';
                $is_holiday = 1;
            } elseif($time_diff > $policy->delay_time && $time_diff < $policy->ext_delay_time){
                $flag = 'D';
            } elseif($time_diff > $policy->ext_delay_time){
                $flag = 'E';
            }
        }
        return;
    }

    private function checkingHolidaySetup($company_id, &$flag, &$is_holiday, &$setting_id){
        $rows =  DB::table('holiday_settings')
            ->selectRaw("id")
            ->where([ 'company_id' => $company_id ])
            ->where(function ($q) {
                $q->where('from_date', '<=', date('Y-m-d'));
                $q->where('to_date', '>=', date('Y-m-d'));
            })
            ->first();
        if(!empty($rows)) {
            $flag = 'H';
            $is_holiday = 1;
            $setting_id = $rows->id;
        }
        return;
    }

    private function getAttendancePolicy($company_id){
        $day_name = date('l', strtotime(date("Y-m-d")));
        $date = date('Y-m-d');
        // DATE_FORMAT(ap.in_time,'%H:%i') as in_time,
        $rows =  DB::table('attendance_policy_head as aph')
            ->selectRaw("
                ap.in_time,
                ap.delay_time,
                ap.extream_delay_time as ext_delay_time, working_type
            ")
            ->join('attendance_policy as ap', 'ap.attendence_head_id', '=', 'aph.id')
            ->where([ 'aph.company_id' => $company_id, 'aph.status' => 1, 'ap.day_name' => $day_name ])
            ->whereRaw("( DATE(aph.effective_from) >= {$date} )")
            ->first();
        return $rows;
    }

}
