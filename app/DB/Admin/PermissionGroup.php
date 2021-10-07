<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PermissionGroup
 * @package App\DB\Admin
 */
class PermissionGroup extends Model
{
    /**
     * @var string
     */
    protected $table = 'permission_groups';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @param model,
     * @param foreign_key,
     * @param local_key
     */
    public function permissions(){
        return $this->hasMany('App\DB\Admin\Permission');
    }
}
