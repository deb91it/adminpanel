<?php

namespace App\DB\Api;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';

    public function adminUser()
    {
        return $this->hasOne('App\DB\Admin\AdminUser');
    }

 /*   public function roles()
    {
        return $this->hasOne('App\DB\Admin\Role');
    }*/

    public function attachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
    }

    public function member(){
        return $this->belongsTo('App\DB\Admin\Member','member_id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\DB\Admin\Role','role_member','member_id','role_id');
    }
}
