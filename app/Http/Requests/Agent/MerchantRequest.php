<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\Request;

class MerchantRequest extends Request
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
            'email'         => 'email',
            'mobile_no'     => 'required|unique:members,mobile_no,NULL,id,user_type,3|min:11|max:11',
            'password'      => 'required|min:6|max:60',
            'language'      => 'required|numeric',
           // 'gender'        => 'required|numeric',
            'country'       => 'required',
            'city'          => 'required|numeric'
        ];
    }
}
