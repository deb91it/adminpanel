<?php namespace App\Services\Traits\Admin;
use App\DB\Permission;
/**
 * Created by PhpStorm.
 * user: MAMUN AHMED
 * Date: 25-Aug-15
 * Time: 11:32 PM
 */
trait UseerAccess{



    public function can_access($permission_string)
    {
        $user_id = get_logged_user_id();

        $perPage = 20;



        // if user has the role and permission
        // then return true
        // else
        // return to dashbord or the referrer and show 'You dont have permission to access this page'

        $role_id = $this->has_role($user_id);

        // if Administrator role
        if ($role_id === '1')
            return true;

        /*$res = $CI->users->get_by_role_id_and_slug($role_id,$slug);
        if( count($res) > 0){
            return true;
        }else{
            if( $redirect )
            {
                $CI->session->set_userdata(array('error_message'=>"You don't have permission to access this page!"));
                redirect(base_url().'admin_dashboard','refresh');
            }
            else
                return false;
            //$CI->output->set_header("Location: ".base_url().'admin/dashboard', TRUE, 302);
        }*/
    }
}