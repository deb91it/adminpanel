<?php

namespace App\Services;

/**
 * Created by PhpStorm.
 * user: MAMUN AHMED
 * Date: 26-Aug-15
 * Time: 11:31 AM
 */
use DB;
use App\User;
use App\DB\Admin\Role;
use App\DB\Admin\Permission;
use App\DB\Admin\PermissionGroup;

class Access {

    public function can($permission_slug){
        $user_id = get_logged_user_id();
        //If user is admin
        if ($user_id == 1) return true;
        //Get user role id
        $role_id = $this->hasRole($user_id);
        //If user role is admin
        if ($role_id == 1) return true;
        $roles = $this->has_permission($role_id,$permission_slug);
        if(count($roles) > 0) {
            return true;
        }
        return false;
    }

    private function hasRole($user_id){
        return DB::table('role_member')->where('member_id', $user_id)->value('role_id');
    }

    private function has_permission($role_id,$permission_slug){
        $permissions = DB::table('role_permissions')
            ->where('role_id', $role_id)
            ->where('permissions','like', "%".$permission_slug."%")
            ->get();
        return $permissions;

        //in Codeigniter: SELECT * FROM (`role_permission_relation` as r) WHERE `r`.`role_id` = '5' AND `r`.`permission` LIKE '%manage\_home%'
        //in laravel: select * from `role_permission` where `role_id` = '1' and `permissions` like '%manage_product%'
    }


}