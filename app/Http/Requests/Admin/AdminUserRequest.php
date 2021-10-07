<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AdminUserRequest extends Request
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
     * exists:users,email
     */
    public function rules()
    {
        return [
            'first_name' => 'required|min:3|max:100',
            'last_name'  => 'required|min:3|max:100',
            'email'      => 'required|unique:members,email,NULL,id,user_type,0|email',
            'mobile_no'  => 'required|unique:members,mobile_no,NULL,id,user_type,0|min:11|max:11',
            'password'   => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6',
            'user_role'  => 'required',
            'is_active'  => 'required|numeric',
            'can_login'  => 'required|numeric',
//            'hub_id'  => 'required|numeric'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'user_role.required' => 'Please select user role',
        ];
    }
}
