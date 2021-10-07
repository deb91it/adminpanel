<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class DeliveryProducts extends Model
{
    protected $table = 'delivery_product';


//    public function companies()
//    {
//        return $this->belongsToMany('App\DB\Admin\Company','agent_company','company_id','agent_id');
//    }
}
