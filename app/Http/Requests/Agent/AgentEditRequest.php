<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\Request;

class AgentEditRequest extends Request
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
            'username'      => "unique:members,username,{$id},id,user_type,4|min:5|max:50",
            'email'         => "required|email|unique:members,email,{$id},id,user_type,4",
            'mobile_no'     => "required|unique:members,mobile_no,{$id},id,user_type,4|min:11|max:11",
            // 'password'      => 'required|min:6|max:60',
            'language'      => 'required|numeric',
            'country'       => 'required',
            'city'          => 'required|numeric'
        ];
    }
}
