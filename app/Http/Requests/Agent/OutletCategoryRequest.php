<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\Request;

class OutletCategoryRequest extends Request
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
            'name'    => 'required|max:50',
            'category_image'     => 'mimes:jpeg,jpg,png|max:1000',

        ];
    }
}
