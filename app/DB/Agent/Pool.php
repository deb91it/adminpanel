<?php

namespace App\DB\Agent;

use Illuminate\Database\Eloquent\Model;

class Pool extends Model
{
    protected $table = 'pools';

    public function passengers()
    {
        return $this->belongsToMany('App\DB\Agent\Member','pool_member', 'pool_id', 'member_id');
    }

    public function drivers()
    {
        return $this->belongsToMany('App\DB\Agent\Member','pool_member','pool_id' ,'member_id')->withPivot('user_type');
    }

    public function members()
    {
        return $this->belongsToMany('App\DB\Agent\Member','pool_member','pool_id' ,'member_id');
    }
}
