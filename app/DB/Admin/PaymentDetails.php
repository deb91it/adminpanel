<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    protected $table = 'payment_details';


//    public function companies()
//    {
//        return $this->belongsToMany('App\DB\Admin\Company','agent_company','company_id','agent_id');
//    }
}
