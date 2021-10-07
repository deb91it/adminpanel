<?php

namespace App\Http\Controllers\Admin;

use App\DB\Admin\SettingsMain;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Admin\SettingsMainRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\SystemSettings\SystemSettingsRepository;
use DB;

class SystemSettingsController extends Controller
{
    protected $setting;

    function __construct(
        SystemSettingsRepository $setting
    )
    {
        $this->setting = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = $this->setting->getData();
        return view('admin.system-settings.index',compact('settings'));
    }

    public function create(){
        $companies = DB::table('companies')->select('id','name')->get();
        return view('admin.system-settings.create',compact('companies'));
    }

    public function check(Request $request){
        $checkSettings = DB::table('settings_main')->where('company_id',$request->company_id)->first();
        if (!empty($checkSettings)){
            return json_encode(false);
        }else{
            return json_encode(true);
        }
    }

    public function store(SettingsMainRequest $request){
        $settings = $this->setting->store($request);
        if ($settings > 0){
            return redirect('admin/system-settings')->with('flashMessageSuccess','The settings has successfully created !');
        }
        return redirect('admin/system-settings')->with('flashMessageError','Unable to create settings');
    }

    public function update(SettingsMainRequest $request){
        $settings = $this->setting->update($request);
        if ($settings > 0){
            return redirect('admin/system-settings')->with('flashMessageSuccess','The settings has successfully update !');
        }
        return redirect('admin/system-settings')->with('flashMessageError','Unable to update settings');
    }

    public function edit($company_id){
        $companies = DB::table('settings_main as sm')
            ->select('com.name','com.logo_url','com.id')
            ->join('companies as com','com.id','=','sm.company_id')
            ->groupBy('sm.company_id')
            ->where('sm.company_id',$company_id)
            ->first();
        $getData = DB::table('settings_main')->where('company_id',$company_id)->get();
        return view('admin.system-settings.edit',compact('companies','getData','company_id'));
    }

    public function details($company_id){
        $getData = DB::table('settings_main')->where('company_id',$company_id)->get();
        return response()->json($getData);
    }

}