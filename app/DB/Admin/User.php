<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
   // protected $fillable = ['first_name' ,'last_name' ,'email','mobile','password', 'confirm_pass', 'user_type', 'user_role'];
    /**
     * Many-To-Many Relationship Method for accessing the user->roles
     *
     * @return QueryBuilder Object
     */
    public function roles()
    {
        return $this->belongsToMany('App\DB\Admin\Role','role_user','user_id','role_id');
    }

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
}
