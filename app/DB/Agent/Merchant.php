<?php

namespace App\DB\Agent;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'merchants';

    public function vehicles(){
        return $this->hasMany('App\DB\Admin\Vehicle');
    }
}
