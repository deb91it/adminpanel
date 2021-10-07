<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package App\DB\Admin
 */
class Role extends Model
{
    /**
     * @var string
     */
    protected $table = 'roles';
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany('App\DB\Admin\Member','role_member', 'role_id', 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
   /* public function permissions(){
        return $this->belongsToMany('App\DB\Admin\Permission','role_permissions','role_id','permission_id');
    }*/

    public function permission()
    {
        return $this->hasOne('App\DB\Admin\RolePermission');
    }

    public function company()
    {
        return $this->hasOne('App\DB\Admin\Company');
    }
}
