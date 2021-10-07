<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'merchants';


    public function member()
    {
        return $this->belongsTo('App\DB\Admin\Member','member_id');
    }
}
