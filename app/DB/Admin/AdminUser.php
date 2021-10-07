<?php

namespace App\DB\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
   // protected $fillable = ['first_name' ,'last_name' ,'email','mobile','password', 'confirm_pass', 'user_type', 'user_role'];
    /**
     * Many-To-Many Relationship Method for accessing the user->roles
     *
     * @return QueryBuilder Object
     */
    protected $table = 'admin_users';
}
