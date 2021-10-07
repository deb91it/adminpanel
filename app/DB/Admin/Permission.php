<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission_group(){
        return $this->belongsTo('App\DB\Admin\PermissionGroup','permission_group_id');
    }

}
