<?php namespace App\Repositories\Agent\HolidaySettings;

use App\DB\Agent\HolidaySettings;
use DB;
use Datatables;

class EloquentHolidaySettingsRepository implements HolidaySettingsRepository
{
    protected $holiday_settings;
    protected $role;
    protected $company_id;

    function __construct(HolidaySettings $holiday_settings)
    {
        $this->holiday_settings = $holiday_settings;
        $this->company_id = get_agent_company_id();
    }

    public function getReportPaginated($request, $company_id){
        $query = DB::table('holiday_settings as hs')
            ->select(
                'hs.id as hs_id', 'hs.title as title', 'hs.description as description',
                DB::raw("DATE_FORMAT(hs.from_date,'%M %d %Y') AS from_date"),
                DB::raw("DATE_FORMAT(hs.to_date,'%M %d %Y') AS to_date"), 'hs.no_of_days as total_days'
            )
            ->where(['hs.company_id' => $this->company_id, 'hs.status' => 1]);

        return Datatables::of($query)
            ->addColumn('action_col', function ($holiday_settings_data) {
                return '
                    <a href="'.route('agent.holiday-settings.edit',array($holiday_settings_data->hs_id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="'.route('agent.holiday-settings.delete',array($holiday_settings_data->hs_id)).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="fa fa-trash"></i></a>';
            })
            ->make(true);
    }

    public function store($input)
    {
        $input_from_date = $input['from_date'];
        $fromDate = date("Y-m-d", strtotime($input_from_date));
        $fromDate = date_create($fromDate);

        $input_to_date = $input['to_date'];
        $toDate = date("Y-m-d", strtotime($input_to_date));
        $toDate = date_create($toDate);

        $diff = date_diff($fromDate, $toDate);
        $totalDays = $diff->format("%R%a") + 1;

        $holiday_settings = new HolidaySettings();
        $holiday_settings->title        = $input['title'];
        $holiday_settings->description  = $input['description'];
        $holiday_settings->from_date    = $fromDate;
        $holiday_settings->to_date      = $toDate;
        $holiday_settings->no_of_days   = $totalDays;
        $holiday_settings->company_id   = $this->company_id;
        $holiday_settings->created_at   = date('Y-m-d');
        $holiday_settings->created_by   = get_logged_user_id();
        if ($holiday_settings->save()) {
            return $holiday_settings->id;
        }
        return 0;
    }
    
    public function findOrThrowException($id)
    {
        return  DB::table('holiday_settings as hs')
            ->select('*')
            ->where(['hs.id' => $id])
            ->first();
    }

    public function update($input, $id)
    {
        $input_from_date = $input['from_date'];
        $fromDate = date("Y-m-d", strtotime($input_from_date));
        $fromDate = date_create($fromDate);

        $input_to_date = $input['to_date'];
        $toDate = date("Y-m-d", strtotime($input_to_date));
        $toDate = date_create($toDate);

        $diff = date_diff($fromDate, $toDate);
        $totalDays = $diff->format("%R%a") + 1;

        $holiday_settings = HolidaySettings::where('id', $id)->first();
        $holiday_settings->title        = $input['title'];
        $holiday_settings->description  = $input['description'];
        $holiday_settings->from_date    = $fromDate;
        $holiday_settings->to_date      = $toDate;
        $holiday_settings->no_of_days   = $totalDays;
        $holiday_settings->company_id   = $this->company_id;
        $holiday_settings->created_at   = date('Y-m-d');
        $holiday_settings->created_by   = get_logged_user_id();
        if ($holiday_settings->save()) {
            return true;
        }
        return 0;
    }

    public function delete($id)
    {
        DB::table('holiday_settings')
            ->where('id', $id)
            ->update([
                'status' => 0,
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => get_logged_user_id()
            ]);
        return true;
    }
}
