<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AgentRequest extends Request
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
        return [
            'first_name'    => 'required|min:3|max:50',
            'last_name'     => 'required|min:3|max:50',
            'email'         => 'required|email|unique:members,email,NULL,id,user_type,1',
            'mobile_no'     => 'required|unique:members,mobile_no,NULL,id,user_type,1|min:11|max:11',
            'password'      => 'required|min:6|max:60',
            'nid'       => 'required',
            'zone_id'          => 'required|numeric',
        ];
    }
}
