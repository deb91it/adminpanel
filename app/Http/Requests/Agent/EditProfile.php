<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\Request;

class EditProfile extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = get_logged_user_id();

        return [
            'first_name'    => 'required|min:3|max:50',
            'last_name'     => 'required|min:3|max:50',
            'email'         => "required|email|unique:members,email,{$id},id,user_type,4",
            'mobile_no'     => "required|unique:members,mobile_no,{$id},id,user_type,4|min:11|max:11",
            'username'      => "unique:members,username,{$id},id,user_type,4|min:3|max:50",
            'gender'        => 'required|numeric',
            'profile_pic'   => 'mimes:jpg,jpeg,png'
        ];
    }
}