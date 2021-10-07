<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

//    public function company(){
//        return $this->belongsTo('App\DB\Admin\Company','compa_id');
//    }

    public function agents()
    {
        return $this->belongsToMany('App\DB\Admin\Agent','agent_company','company_id','agent_id');
    }
}
