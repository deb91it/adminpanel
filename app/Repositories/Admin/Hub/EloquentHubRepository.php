<?php namespace App\Repositories\Admin\Hub;
use App\DB\Admin\Hub;
use App\DB\Permission;
use DB;
use PDF;
use Datatables;


class EloquentHubRepository implements HubRepository
{
    protected $Hub;

    function __construct(Hub $Hub)
    {
        $this->Hub = $Hub;
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
    public function store($input)
    {
        $hub_pic='';
        $hub_pic_url='';

        if ($input->hasfile('hub_image')) {
            $save_path = public_path('resources/hub_image/');
            $file = $input->file('hub_image');
            $image_name = $input['hub_name']."-".time().".".$file->getClientOriginalExtension();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();


            //Update DB Field
            $hub_pic      = $image_name;
            $hub_pic_url    = asset('resources/hub_image/'.$image_name);
        }

        $Hub = new Hub();
        $Hub->hub_name              = $input['hub_name'];
        $Hub->address               = $input['address'];
        $Hub->hub_image             = $hub_pic;
        $Hub->hub_image_url         = $hub_pic_url;
        $Hub->hub_code              = getUniqueHubCode(6);
        $Hub->created_at            = date('Y-m-d H:i:s');
        if ($Hub->save()) {
            return $Hub->id;
        }
        return 0;
    }

    public function destroy($id)
    {
        $hub = Hub::find($id);
        if (!empty($hub))
        {
            $hub->status = 0;
            $hub->save();
            return $hub->id;
        }
        return 0;
    }

    public function update($input, $id){
        $hub_pic = '';
        $hub_pic_url = '';

        $Hub = Hub::where('id', $id)->first();
        if ($input->hasfile('hub_image')) {
            $save_path = public_path('resources/hub_image/');
            $file = $input->file('hub_image');
            $image_name = $input['hub_name']."-".time()."-".$file->getClientOriginalExtension();

            $file->move($save_path, $image_name);
            $image = \Image::make(sprintf($save_path.'%s', $image_name))->resize(200, 200)->save();
            $image_mime = \Image::make($save_path.$image_name)->mime();

            //Delete existing image
            if (\File::exists($save_path.$Hub->hub_image))
            {
                \File::delete($save_path.$Hub->hub_image);
            }

            //Update DB Field
            $hub_pic      = $image_name;
            $hub_pic_url    = asset('resources/hub_image/'.$image_name);
        }
        if (empty($Hub->hub_code))
        {
            $Hub->hub_code          = getUniqueHubCode(6);
        }
        $Hub->hub_name              = $input['hub_name'];
        $Hub->address               = $input['address'];
        $Hub->hub_image             = $hub_pic;
        $Hub->hub_image_url         = $hub_pic_url;
        $Hub->status                = $input['status'];
        $Hub->created_at            = date('Y-m-d H:i:s');
        if ($Hub->save()) {
            return $Hub->id;
        }
        return 0;
    }

    public function delete($id)
    {
        DB::table('Hub')
            ->where('id', $id)
            ->update([
                'status' => 0,
            ]);
        return true;
    }



    public function getReportPaginated($request){

        $query = DB::table('hub')
            ->select('hub.*')
            ->orderBy('hub.id','desc');

        return Datatables::of($query)
            ->addColumn('action_col', function ($user) {
                return '
                    <a href="'.route('admin.hub.edit',array($user->id)).'"  class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit Hub"><i class="fa fa-edit"></i></a>
                    <a href="'.route('admin.hub.delete',array($user->id)).'"  class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete Hub"><i class="fa fa-trash"></i></a>
                    ';
            })
            ->make(true);
    }


}
