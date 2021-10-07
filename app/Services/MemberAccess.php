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

class MemberAccess {

    public function check_member_token_validity($token, $client){

        $token_info = DB::table('member_tokens as mt')
            ->join('members as m', 'm.id', '=', 'mt.member_id')
            ->where(['m.is_active' => 1, 'm.can_login' => 1, 'm.status' => 1])
            ->where(['mt.token' => $token, 'mt.client' => $client])
            ->where('mt.expire_at', '>', date("Y-m-d H:i:s"))
            ->where('mt.is_expire', 0)
            ->orderBy('mt.id', 'desc')
            ->first();
        if ($token_info != null) {
            return $token_info->member_id;
        }
        return null;
    }

    public function can($permission_slug, $member_id){
        //Get member role id
        $role_id = $this->hasRole($member_id);
        $roles = $this->has_permission($role_id, $permission_slug);
        if(count($roles) > 0) {
            return true;
        }
        return false;
    }

    private function hasRole($member_id){
        return DB::table('role_member')->where('member_id', $member_id)->value('role_id');
    }

    private function has_permission($role_id, $permission_slug){
        $permissions = DB::table('role_permissions')
            ->where('role_id', $role_id)
            ->where('permissions','like', "%".$permission_slug."%")
            ->get();
        return $permissions;
    }

}