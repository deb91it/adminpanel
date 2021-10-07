<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $table = 'deliveries';


    public function merchant()
    {
        return $this->belongsTo('App\DB\Admin\Merchant','merchant_id');
    }
}
