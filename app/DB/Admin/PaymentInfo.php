<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class PaymentInfo extends Model
{
    protected $table = 'payment_info';


//    public function companies()
//    {
//        return $this->belongsToMany('App\DB\Admin\Company','agent_company','company_id','agent_id');
//    }
}
