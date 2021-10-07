<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CompanyRequest extends Request
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
            'name'          => 'required|min:3|max:50',
            'moto'          => 'min:3|max:50',
            'address'       => 'required|min:3|max:50',
            'logo'          => 'required|mimes:jpg,jpeg,png',
            'agent'         => 'required|array',
            'country'       => 'required',
            'city'          => 'required|numeric'
        ];
    }
}
