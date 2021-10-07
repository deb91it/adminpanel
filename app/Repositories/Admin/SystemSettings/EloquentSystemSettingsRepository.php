<?php namespace App\Repositories\Admin\SystemSettings;

use App\DB\Admin\SettingsMain;
use App\DB\Admin\SystemSettings;
use DB;

class EloquentSystemSettingsRepository implements SystemSettingsRepository
{
    public function getData(){
        return DB::table('settings_main as sm')
            ->select('com.name','com.logo_url','com.id')
            ->join('companies as com','com.id','=','sm.company_id')
            ->groupBy('sm.company_id')
            ->paginate(15);
    }

    public function store($inputs){
        foreach ($inputs['config_key'] as $key=>$inp){
            $sett = new SettingsMain();
            $sett->config_key = $inputs['config_key'][$key];
            $sett->config_value = $inputs['config_value'][$key];
            $sett->company_id = $inputs['company_id'];
            $sett->save();
        }
        return 1;
    }

    public function update($inputs){
        foreach ($inputs['config_key'] as $key=>$inp){
            $sett = SettingsMain::find($inputs['row_id'][$key]);
            $sett->config_key = $inputs['config_key'][$key];
            $sett->config_value = $inputs['config_value'][$key];
            $sett->company_id = $inputs['company_id'];
            $sett->save();
        }
        return 1;
    }

}