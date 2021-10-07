<?php

namespace App\Http\Controllers\Agent;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Agent\HolidaySettings\HolidaySettingsRepository;
use App\Http\Requests\Agent\HolidaySettingsRequest;
use DB;
use Validator;
use Illuminate\Support\Facades\Hash;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class HolidaySettingsController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $holiday_settings;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;
    protected $agent_id;
    protected $company_id;

    /**
     * AgentController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        HolidaySettingsRepository $holiday_settings
    )
    {
        $this->holiday_settings = $holiday_settings;
        $this->company_id = get_agent_company_id();
    }

    public function index(Request $request)
    {
        return view('agent.holiday-settings.index');
    }

    public function getDataTableReport(Request $request){
        return $this->holiday_settings->getReportPaginated($request, $this->company_id);
    }

    public function create()
    {
        return view('agent.holiday-settings.create');
    }

    public function store(HolidaySettingsRequest $request)
    {
            $holiday_settings_id = $this->holiday_settings->store($request);
            if ($holiday_settings_id > 0) {
                return redirect('holiday-settings')->with('flashMessageSuccess','Holidays setting has successfully created !');
            }
        return redirect('holiday-settings')->with('flashMessageError','Unable to create Holidays settings');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('agent.holiday-settings.edit')
            ->withHolidays($this->holiday_settings->findOrThrowException($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HolidaySettingsRequest $request, $id)
    {
            $agent = $this->holiday_settings->update($request, $id);
            if ($agent) {
                return redirect('holiday-settings')->with('flashMessageSuccess','Holiday successfully updated.');
            }
        return redirect('employee')->with('flashMessageError','Unable to updated holiday');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->holiday_settings->delete($id);
        return redirect('holiday-settings')->with('flashMessageSuccess','Holidays successfully deleted.');
    }
}
