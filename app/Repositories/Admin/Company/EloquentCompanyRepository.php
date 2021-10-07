<?php namespace App\Repositories\Admin\Company;
use App\DB\Admin\Company;
use App\DB\Admin\Member;
use App\DB\Permission;
use DB;
use Datatables;


class EloquentCompanyRepository implements CompanyRepository
{
    protected $company;

    function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc')
    {
        // TODO: Implement getAll() method.
    }

    public function getById($id, $status = 1)
    {
        // TODO: Implement getById() method.
    }

    public function create($inputs)
    {
        // TODO: Implement create() method.
    }

    public function update($input, $id)
    {
        $company = $this->company->find($id);

        if ($input->hasfile('logo')) {
            $save_path = public_path('resources/company_logo/');
            $file = $input->file('logo');
            $image_name = $input['name']."-".time()."-".$file->getClientOriginalName();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();

            //Delete existing image
            if (\File::exists($save_path.$company->logo))
            {
                \File::delete($save_path.$company->logo);
            }

            //Update DB Field
            $company->logo              = $image_name;
            $company->logo_mime_type    = $image_mime;
            $company->logo_url          = url('resources/company_logo/'. $image_name);
        }

        $company->name        = $input['name'];
        $company->moto        = $input['moto'];
        $company->group_name  = $input['group_name'];
        $company->address     = $input['address'];
        $company->zone_id     = $input['city'];
        $company->updated_at  = date('Y-m-d H:i:s');
        $company->updated_by  = get_logged_user_id();

        if ($company->save()) {
            $company->agents()->sync($input['agent']);
            $this->insertCompanyIdToAgentTable($input['agent'], $id);
            return true;
        }
        return false;
    }

    private function insertCompanyIdToAgentTable($agent_id, $company_id) {
        DB::table('agents')->where('id', $agent_id)->update(['company_id' => $company_id]);
        return;
    }

    private function changeAgentHasCompanyFiled($agent_id_array) {
        if (! empty($agent_id_array)) {
            foreach ($agent_id_array as $id) {
                DB::table('agents')->where('id', $id)->update(['has_company' => 1]);
            }
        }
        return;
    }

    public function delete($id)
    {
        DB::table('companies')
            ->where('id', $id)
            ->update([
                'status' => 2,
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => get_logged_user_id()
            ]);
        return true;
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }

    public function getUserDetails($member_id)
    {
        return $this->company->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $company_id)
    {
        return ['details' => 'Nothing found here'];
    }

    public function getReportPaginated($request){

        $start_date = '';
        $end_date = '';
        $date_range = $request->get('columns')[8]['search']['value'];

        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (! date_validate($start_date)  || ! date_validate($end_date)) {
                $start_date = '';
                $end_date = '';
            }
        }

        $query = DB::table('companies as com')
            ->select(
                'com.id as new_id',
                'com.*',
                DB::raw("(SELECT 
                    GROUP_CONCAT(CONCAT(ag.first_name, '  ', ag.last_name) SEPARATOR ', ') AS agent 
                FROM
                    agents AS ag
                        INNER JOIN
                    agent_company AS ac ON ac.agent_id = ag.id
                WHERE
                    ac.company_id = new_id) AS agent")
            )->where('com.status', '!=', 2);
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('com.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }

        return Datatables::of($query)
            ->filterColumn('agent', function($query, $keyword) {
                $query->whereRaw("CONCAT(ag.first_name, ag.last_name) like ?", ["%{$keyword}%"]);
            })
            ->addColumn('logo', function ($com) {
                return '<img src="'. url('/resources/company_logo'). '/'. $com->logo . '" height="50px" width="45px">';
            })
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="'.route('admin.company.edit',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="'.route('admin.company.delete',array($user->id)).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete" onclick="return confirm(\'Are you sure, want to delete ?\')"><i class="fa fa-trash"></i></a>';
            })
            ->make(true);
    }

    public function store($input)
    {
        //Upload Picture
        $image_name = '';
        $image_mime = '';
        $save_path = '';

        if ($input->hasfile('logo')) {
            $save_path = public_path('resources/company_logo/');
            $file = $input->file('logo');
            $image_name = $input['name']."-".time()."-".$file->getClientOriginalName();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();
        }

        $company = new Company();
        $company->name        = $input['name'];
        $company->moto        = $input['moto'];
        $company->group_name        = $input['group_name'];
        $company->address        = $input['address'];
        $company->logo              = $image_name;
        $company->logo_mime_type    = $image_mime;
        $company->logo_url          = url('resources/company_logo/'. $image_name);
        $company->zone_id     = $input['city'];
        $company->created_at   = date('Y-m-d H:i:s');
        $company->created_by   = get_logged_user_id();

        if ($company->save()) {
           // $company->agents()->attach($input['agent']);
            $company->agents()->sync($input['agent']);
            $this->insertCompanyIdToAgentTable($input['agent'], $company->id);
            return $company->id;
        }
        return 0;
    }
    
    public function findOrThrowException($id)
    {
        return DB::table('companies as com')
            ->select(
                'com.*',
                DB::raw('CONCAT(ag.first_name, " ", ag.last_name) AS agent'),
                'ag.id as agent_id',
                'z.zone_id', 'z.name as zone_name',
                'c.country_id', 'c.name as country_name'
            )->LeftJoin('agent_company as ac', 'ac.company_id', '=', 'com.id')
            ->LeftJoin('agents as ag', 'ag.id', '=', 'ac.agent_id')
            ->LeftJoin('zones as z', 'z.zone_id', '=', 'com.zone_id')
            ->LeftJoin('countries as c', 'c.country_id', '=', 'z.country_id')
            ->where('com.id', $id)
            ->where('com.status', 1)
            ->first();
    }


    public function exportFile($request)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        DB::setFetchMode(\PDO::FETCH_ASSOC);

        $query = DB::table('companies as com')
            ->select(
                'com.id as Id',
                'com.name as Name',
                'com.group_name as Group_name',
                'com.moto as Moto',
                'com.address as Address',
                DB::raw("(SELECT 
                    GROUP_CONCAT(CONCAT(ag.first_name, '  ', ag.last_name) SEPARATOR ', ') AS agent 
                FROM
                    agents AS ag
                        INNER JOIN
                    agent_company AS ac ON ac.agent_id = ag.id
                WHERE
                    ac.company_id = com.id) AS agent"),
                'com.created_at as Join_at'
            );
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('com.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }
        $data = $query->get();
        return $data;
    }
}
