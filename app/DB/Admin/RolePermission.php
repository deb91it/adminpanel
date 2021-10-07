<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';

    public function role()
    {
        return $this->belongsTo('App\DB\Admin\Role','role_id');
    }
}
