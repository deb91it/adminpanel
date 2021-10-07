<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class StoreRiderRequest extends Request
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
            'flag_status_id'        => 'required',
            'assign_to'        => 'required_if:flag_status_id,==,10',
        ];
    }

    public function messages()
    {
        return[
            'flag_status_id.required' => 'This field is required.',
            'assign_to.required_if' => 'This field is required.',
        ];

    }
}
