<?php

namespace App\DB\Agent;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';

    protected $fillable = ['merchant_id'];

    public function merchant(){
        return $this->belongsTo('App\DB\Agent\Merchant','merchant_id');
    }
}
