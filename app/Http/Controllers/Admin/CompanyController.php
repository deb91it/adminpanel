<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\Company\CompanyRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\CompanyRequest;
use App\Http\Requests\Admin\CompanyEditRequest;
use Excel;
use DB;
use App\DB\Admin\Company;

class CompanyController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $company;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * CompanyController constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        CompanyRepository $company
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->company = $company;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }

    public function index(Request $request)
    {
        return view('admin.company.index');
    }

    public function getDataTableReport(Request $request){
        return $this->company->getReportPaginated($request);
    }

    public function postExportFile(Request $request)
    {
        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-company-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-company-from-' . $start_date . '-To-' . $end_date;
        }

        // $data = [ 'Nmae' => "Mamun"];
        $data = $this->company->exportFile($request);

        if (empty($data)) {
            $this->response['success'] = false;
            $this->response['msg']  = "Didn't found any data !";
            return response($this->response,200);
        }

        return Excel::create($file_name, function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->store($export_type, 'exports/', true);
    }

    public function create()
    {
        return view('admin.company.create')
            ->withAgents(DB::table('agents')->where('is_admin', 1)->select(DB::raw('CONCAT(first_name, " ", last_name) AS name'), 'id')->orderBy('id', 'ASC')->get())
            ->withCountries($this->address->getAllCountries());
    }

    public function store(CompanyRequest $request)
    {
        $company_id = $this->company->store($request);
        if ($company_id > 0) {
            if ($company_id > 0) {
                return redirect('admin/company')->with('flashMessageSuccess','The company has successfully created !');
            }
        }
        return redirect('admin/company')->with('flashMessageError','Unable to create company');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id_array = [];
        $company = $this->company->findOrThrowException($id);
        $get_agent_ids = DB::table('agent_company')->select('agent_id')->where('company_id', $id)->get();
        if (! empty($get_agent_ids)) {
            foreach ($get_agent_ids as $k => $v) {
                $id_array[] = $v->agent_id;
            }
        }

        return view('admin.company.edit')
            ->withAgents(DB::table('agents')->where('is_admin', 1)->select(DB::raw('CONCAT(first_name, " ", last_name) AS name'), 'id')->orderBy('id', 'ASC')->get())
            ->withOwnagent($id_array)
            ->withCompany($company)
            ->withZone($this->address->getZoneListByCountryId($company->country_id))
            ->withCountries($this->address->getAllCountries());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyEditRequest $request, $id)
    {
        $member = $this->company->update($request, $id);
        if ($member) {
            return redirect('admin/company')->with('flashMessageSuccess', 'The company successfully updated !');
        }
        return redirect('admin/company')->with('flashMessageError', 'Unable to updated company !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->company->delete($id);
        return redirect('admin/company')->with('flashMessageSuccess','The company successfully deleted.');
    }

    public function getCompanyDetails(Request $request)
    {
        $company_id    = $request['company_id'];
        $member_id      = $request['member_id'];

        $this->response['company'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getCompanyVehicleInfo($company_id);
        $this->response['driver']   = $this->getCompanyDriverInfo($company_id);

        return response($this->response,200);
    }
}

