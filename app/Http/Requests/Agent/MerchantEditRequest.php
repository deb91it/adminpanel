<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\Request;

class MerchantEditRequest extends Request
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
        $id = $this->segment(2);
        return [
            'first_name'    => 'required|min:3|max:50',
            'last_name'     => 'required|min:3|max:50',
            // 'username'      => 'required|unique:members|min:5|max:50',
            // 'mobile_no'     => 'required|unique:members|min:11|max:11',
            // 'password'      => 'required|min:6|max:60',

            'username'      => "unique:members,username,{$id},id,user_type,3||min:5|max:50",
            'email'         => "email|unique:members,email,{$id},id,user_type,3",
            'mobile_no'     => "required|unique:members,mobile_no,{$id},id,user_type,3|min:11|max:11",
            'language'      => 'required|numeric',
            'country'       => 'required',
            'city'          => 'required|numeric'
        ];
    }
}
