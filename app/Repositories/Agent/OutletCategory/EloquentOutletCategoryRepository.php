<?php namespace App\Repositories\Agent\OutletCategory;
use App\DB\Admin\Agent;
use App\DB\Admin\Member;
use App\DB\Agent\OutletCategories;
use App\DB\Api\Store;
use App\DB\Permission;
use DB;
use Datatables;
use App\DB\Admin\Role;


class EloquentOutletCategoryRepository implements OutletCategoryRepository
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

    public function update($input)
    {
        $oc = OutletCategories::find($input['rowid']);
        if (!empty($oc)) {
            if ($input->hasfile('category_image')) {
                $save_path = public_path('resources/outlet_category/');
                $file = $input->file('category_image');
                $image_name = $input['name'] . "-" . time() . "-" . $file->getClientOriginalName();

                $file->move($save_path, $image_name);
                $image = \Image::make(sprintf($save_path . '%s', $image_name))->resize(200, 200)->save();
                $image_mime = \Image::make($save_path . $image_name)->mime();
                //Update DB Field
                $oc->category_image = $image_name;
                $oc->category_image_url = $save_path . $image_name;
            }
            $oc->name = $input['name'];
            $oc->company_id = get_agent_company_id();
            $oc->status = 1;
            $oc->updated_at = date('Y-m-d H:i:s');
            if ($oc->save()) {
                return $oc->id;
            }
        }
        return 0;
    }

    public function delete($id)
    {
        $oc = OutletCategories::find($id);
        if (empty($oc)){
            redirect('outlet-category')->with('flashMessageError','Unable to delete outlet category');
        }
        $oc->status = 0;
        $oc->save();
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
        return $this->agent->where(['status' => 1, 'member_id' => $member_id])->first();
    }

    public function details($member_id, $agent_id)
    {
        return ['details' => 'Nothing found here'];
    }



    public function getReportPaginated($request, $per_page, $agent_id, $status = 1){

        $query=  DB::table('outlet_categories as oc')
            ->select(
                'oc.*','c.name as company_name','c.logo_url as company_logo'
            )->join('companies as c', 'oc.company_id', '=', 'c.id')
//            ->orderBy($order_by, $sort)
            ->where([ 'oc.company_id' => $this->company_id, 'oc.status' => 1 ]);

        return Datatables::of($query)
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="'.route('outlet.category.edit',array($user->id,getLogedinAgentId())).'" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                    <a href="'.route('outlet.category.delete',array($user->id,getLogedinAgentId())).'" class="btn btn-sm m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete" onclick="return confirm(\'Are you sure, want to delete ?\')"><i class="fa fa-trash"></i></a>';
            })
            ->make(true);
    }

    public function store($input, $agent_id)
    {
//        dd($input);exit();
        $oc = new OutletCategories();
        if ($input->hasfile('category_image')) {
            $save_path = public_path('resources/outlet_category/');
            $file = $input->file('category_image');
            $image_name = $input['name']."-".time()."-".$file->getClientOriginalName();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();
            //Update DB Field
            $oc->category_image      = $image_name;
            $oc->category_image_url    = $save_path.$image_name;
        }
        $oc->name  = $input['name'];
        $oc->company_id  = get_agent_company_id();
        $oc->status   = 1;
        $oc->created_at   = date('Y-m-d H:i:s');
        if ($oc->save()) {
            return $oc->id;
        }
        return 0;
    }
    
    public function findOrThrowException($id, $agent_id)
    {
        return  DB::table('members as m')
            ->select($this->getSelectItemDuringEdit())
            ->join('agents as agn', 'agn.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'agn.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->join('zones as z', 'z.zone_id', '=', 'agn.zone_id')
            ->join('countries as c', 'c.country_id', '=', 'z.country_id')
            ->join('languages as l', 'l.id', '=', 'agn.language_id')
            ->where(['m.id' => $id])
            ->first();
    }

    protected function getSelectItemDuringEdit()
    {
        return [
            'm.id as member_id', 'm.username', 'm.email', 'm.mobile_no', 'm.can_login', 'm.is_active',
            'agn.id','agn.first_name', 'agn.last_name','agn.designation','agn.parent_id',
            'r.id as role_id', 'r.role_name',
            'z.zone_id', 'z.name as zone_name',
            'c.country_id', 'c.name as country_name',
            'l.id as language_id', 'l.language_name'
        ];
    }

    public function exportFile($request, $agent_id)
    {
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        DB::setFetchMode(\PDO::FETCH_ASSOC);

        $query = DB::table('members as m')
            ->select(DB::raw(
                'CONCAT(agn.first_name, " ", agn.last_name) as Name'),
                'm.email as Email',
                'm.mobile_no as Mobile',
                'm.username as Username',
                'agn.status as Status',
                'm.can_login as Canlogin',
                'r.role_name as Role',
                'agn.created_at as Joining_date'
            )->join('agents as agn', 'agn.member_id', '=', 'm.id')
            ->join('role_member as rm', 'rm.member_id', '=', 'agn.member_id')
            ->join('roles as r', 'r.id', '=', 'rm.role_id')
            ->where(['agn.parent_id' => $agent_id]);
        if ($start_date != '' && $end_date != '') {
            $query = $query->whereBetween('agn.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
        }
        $data = $query->get();
        return $data;
    }

    public function updateProfile($input, $id)
    {
        $adminUser = Agent::where('member_id', $id)->first();

        if ($input->hasfile('profile_pic')) {
            $save_path = public_path('resources/profile_pic/');
            $file = $input->file('profile_pic');
            $image_name = $input['first_name']."-".$input['last_name']."-".time()."-".$file->getClientOriginalName();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();

            //Delete existing image
            if (\File::exists($save_path.$adminUser->profile_pic))
            {
                \File::delete($save_path.$adminUser->profile_pic);
            }

            //Update DB Field
            $adminUser->profile_pic      = $image_name;
            $adminUser->pic_mime_type    = $image_mime;
        }

        $adminUser->first_name  = $input['first_name'];
        $adminUser->last_name   = $input['last_name'];
        $adminUser->gender      = $input['gender'];
        $adminUser->updated_at  = date('Y-m-d H:i:s');

        if ($adminUser->save()) {
            $member = Member::where('id', $id)->first();
            $member->email = $input['email'];
            $member->mobile_no = $input['mobile_no'];
            $member->username    = $input['username'];
            if ($member->save()) {
                return true;
            }
        }
        return false;
    }

    public function getCompanyRoleList($company_id)
    {
        return ['' => 'Select Role'] + $this->role->where('company_id', $company_id)->orderBy('id', 'asc')->lists('role_name', 'id')->toArray();
    }

    public function getCompanySalesPersonList($company_id)
    {
        return ['' => 'Select Parent']
            + DB::table('agents as a')
                ->select(DB::raw('CONCAT(a.first_name, " ", a.last_name, " [", r.role_name, "]" ) AS agent'), 'a.id as agent_id')
                ->join('members as m', 'm.id', '=', 'a.member_id')
                ->join('role_member as rm', 'rm.member_id', '=', 'm.id')
                ->join('roles as r', 'r.id', '=', 'rm.role_id')
                ->orderBy('a.id', 'asc')
                ->where(['a.company_id' => $company_id, 'a.is_admin' => 0 ])
                ->where('a.depth', '<', 5)
                ->lists('agent', 'agent_id');
    }
}
