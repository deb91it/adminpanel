<?php

namespace App\Http\Requests\Admin;

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
        $id = $this->segment(3);

        return [
            'first_name'    => 'required|min:3|max:100',
            'email'         => "required|email|unique:members,email,{$id},id,user_type,2",
            'mobile_no'     => "required|unique:members,mobile_no,{$id},id,user_type,2|min:11|max:11",
            'id_type'     => 'required',
            'id_number'      => 'required|min:3|max:45',
//            'front_image'      => 'required',
            // 'gender'        => 'required|numeric',
            'business_name'       => 'required',
            'address'          => 'required',
        ];
    }
}
