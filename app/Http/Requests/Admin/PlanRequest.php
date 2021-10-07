<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PlanRequest extends Request
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
            'plan_name'    => 'required|min:3|max:150',
            'plan_type'    => 'required|min:3|max:150',
            'charge'    => 'required|min:2|max:150'
        ];
    }
}
