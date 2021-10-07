<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\Merchant;
use App\DB\Admin\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\Category\CategoryRepository;
use App\Repositories\Admin\Member\MemberRepository;
use App\Repositories\Admin\Role\RoleRepository;
use App\Repositories\Admin\Address\AddressRepository;
use App\Http\Requests\Admin\CategoryRequest;
use Excel;
/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class CategoryController extends Controller
{
    /**
     * @var
     */
    protected $_errors;

    /**
     * @var DriverRepository
     */
    protected $category;

    protected $roles;

    /**
     * @var MemberRepository
     */
    protected $member;
    protected $address;

    /**
     * Category Controller constructor.
     * @param PassengerRepository $passenger
     * @param MemberRepository $member
     */
    function __construct(
        CategoryRepository $category
        , RoleRepository $roles
        , MemberRepository $member
        , AddressRepository $address)
    {
        $this->category = $category;
        $this->roles = $roles;
        $this->member = $member;
        $this->address = $address;
    }
    public function index(Request $request)
    {
        return view('admin.category.index');
    }

    public function postExportFile(Request $request)
    {
        $export_type    = $request['export_type'];
        $format_arr = ['xls','xlsx','csv','pdf'];
        if (! in_array($export_type, $format_arr)) {
            $export_type = 'pdf';
        }
        $file_name = 'Export-Zone-' . date("d-m-Y");
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($start_date != '' && $end_date != '') {
            $file_name = 'Export-Zone-from-' . $start_date . '-To-' . $end_date;
        }

       // $data = [ 'Nmae' => "Mamun"];
        $data = $this->category->exportFile($request);

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

    public function getDataTableReport(Request $request){
        return $this->category->getReportPaginated($request);
    }

    public function create()
    {
        $merchants = Merchant::select('first_name','last_name','id')->get();
        return view('admin.category.create',compact('merchants'));
    }

    public function store(CategoryRequest $request)
    {
        $category_id = $this->category->store($request);
        if ($category_id > 0) {
            return redirect('admin/category')->with('flashMessageSuccess','The Category has successfully created !');
        }
        return redirect('admin/category')->with('flashMessageError','Unable to create Category ');
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
        $category = $this->category->findOrThrowException($id);
        return view('admin.category.edit',compact('category'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $member_id)
    {
        $category = $this->category->update($request, $member_id);
        if ($category) {
            return redirect('admin/category')->with('flashMessageSuccess','The Category successfully updated.');
        }
        return redirect('admin/category')->with('flashMessageError','Unable to updated Category ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->category->delete($id);
        return redirect('admin/category')->with('flashMessageSuccess','The Category successfully deleted.');
    }
    
    public function getZoneDetails(Request $request)
    {
        $category_id    = $request['Zone_id'];
        $member_id      = $request['member_id'];
        
        $this->response['Zone'] = $this->getUserDetails($member_id);
        $this->response['vehicle']  = $this->getZoneVehicleInfo($category_id);
        $this->response['driver']   = $this->getZoneDriverInfo($category_id);
        
        return response($this->response,200);
    }

    public function getZoneByCountryId(Request $request)
    {
        $cities = $this->address->getZoneByCountryId($request['country_id']);
        if ( !empty($cities)) {
            echo"<option value=''>...Select City...</option>";
            foreach($cities as $city)
            {
                echo "<option value='$city->zone_id'> $city->name </option>";
            }
        } else {
            echo"<option value=''>..No Sub Category found ..</option>";
        }
    }
}
