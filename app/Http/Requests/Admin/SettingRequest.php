<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SettingRequest extends Request
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
            'distance_unit'             => 'required|min:2|max:5',
            'provider_time_out'         => 'required|numeric',
            'provider_tolerance'        => 'required|numeric',
            'sms_notification'          => 'required|numeric',
            'push_notification'         => 'required|numeric',
            'email_notification'        => 'required|numeric',
            'email_notification'        => 'required|numeric',
            'referral_code_activation'  => 'required|numeric',
            'bonus_to_refered_user'     => 'required|numeric',
            'bonus_to_referral'         => 'required|numeric',
            'promo_code_activation'     => 'required|numeric',
            'admin_email'               => 'required|email',
            'admin_phone'               => 'required|min:10|max:20',
            'pk_base_fare'              => 'required|numeric',
            'pk_unit_fare'              => 'required|numeric',
            //'pk_waiting_min'            => 'required|numeric',
            'pk_wtng_min_charge'        => 'required|numeric',
            'opk_base_fare'             => 'required|numeric',
            'opk_unit_fare'             => 'required|numeric',
            //'opk_waiting_min'           => 'required|numeric',
            'opk_wtng_min_charge'       => 'required|numeric',
            'merchant_commission_rate'  => 'required|numeric',
        ];
    }
}
