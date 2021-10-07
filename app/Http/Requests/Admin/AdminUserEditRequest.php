<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AdminUserEditRequest extends Request
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
        $id = $this->segment(3);
        
        return [
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'email'         => "required|email|unique:members,email,{$id},id,user_type,0",
            'mobile_no'     => "required|unique:members,mobile_no,{$id},id,user_type,0|min:11|max:11",
           // 'password'  => 'required|confirmed|min:6',
           // 'password_confirmation' => 'required|min:6',
            'user_role' => 'required',
            'is_active'  => 'required|numeric',
            'can_login'  => 'required|numeric'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
           // 'user_type.required' => 'Please enter first name',
            //'last_name.required' => 'Please enter last name',
            'user_type.required' => 'Please select user type',
        ];
    }
}
