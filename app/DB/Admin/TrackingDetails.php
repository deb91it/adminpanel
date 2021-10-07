<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class TrackingDetails extends Model
{
    protected $table = 'tracking_details';
    public $timestamps = false;

    public function companies()
    {
        return $this->belongsToMany('App\DB\Admin\Company','agent_company','company_id','agent_id');
    }
    public function getCreatedAtAttribute($value)
    {
        return date("M j, Y h:i A",strtotime($value));
    }
}