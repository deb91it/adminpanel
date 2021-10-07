<?php

namespace App\Http\Requests\Admin;

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
            'first_name'    => 'required|min:3|max:100',
            'email'         => 'required|email|unique:members,email,NULL,id,user_type,2',
            'mobile_no'     => 'required|unique:members,mobile_no,NULL,id,user_type,2|min:11|max:11',
            'password'      => 'required|min:6|max:60|confirmed',
            'id_type'     => 'required',
            'id_number'      => 'required|min:3|max:45',
            'front_image'      => 'required|mimes:jpeg,jpg,png|max:2000',
            'profile_pic'      => 'mimes:jpeg,jpg,png|max:2000',
            // 'gender'        => 'required|numeric',
            'business_name'       => 'required',
            'address'          => 'required',
//            'password_confirmation'          => 'required|confirmed',
            'payment_type'          => 'required',
            'wallet_provider_mobile' => 'required_if:payment_type,==,1|required_if:payment_type,==,3',
            'account_holder_name_mobile' => 'required_if:payment_type,==,1|required_if:payment_type,==,3',
            'account_number_mobile' => 'required_if:payment_type,==,1|required_if:payment_type,==,3',
            'wallet_provider_bank' => 'required_if:payment_type,==,2|required_if:payment_type,==,3',
            'account_holder_name_bank' => 'required_if:payment_type,==,2|required_if:payment_type,==,3',
            'account_number_bank' => 'required_if:payment_type,==,2|required_if:payment_type,==,3',
            'bank_account_type' => 'required_if:payment_type,==,2|required_if:payment_type,==,3',
            'bank_brunch_name' => 'required_if:payment_type,==,2|required_if:payment_type,==,3',


            //'bank_routing_number' => 'required_if:payment_type,==,2',


        ];
    }

    public function messages()
    {
        return[
            'wallet_provider_mobile.required_if' => 'This field is required.',
            'account_holder_name_mobile.required_if' => 'This field is required.',
            'account_number_mobile.required_if' => 'This field is required.',
            'wallet_provider_bank.required_if' => 'This field is required.',
            'account_holder_name_bank.required_if' => 'This field is required.',
            'account_number_bank.required_if' => 'This field is required.',
            'bank_account_type.required_if' => 'This field is required.',
            'bank_brunch_name.required_if' => 'This field is required.',
            'bank_routing_number.required_if' => 'This field is required.',
            'front_image.max' => 'The front image may not be greater than 2 mb.',
            'profile_pic.max' => 'Merchant profile picture may not be greater than 2 mb.',
        ];

    }
}
