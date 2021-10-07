<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class DeliveryRequest extends Request
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
            'recipient_name'        => 'required|min:3|max:150',
            'recipient_number'      => 'required|min:11|max:11',
            'hub_id'                => 'required',
//            'g_map_recipient_address'  => 'required',
            'merchant_order_id'  => 'required',
            'recipient_zone'        => 'required|numeric',
//            'recipient_email'       => 'email',
            'recipient_address'     => 'required|min:2|max:500',
//            'store'              => 'required|numeric',
            'plan'               => 'required|numeric',
            'plan_returned_id'               => 'required|numeric'
        ];
    }
}
