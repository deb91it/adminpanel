<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ExpenseCategoryRequest extends Request
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
     * exists:users,email
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2|max:100',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'user_type.required' => 'Please select user type',
        ];
    }
}
