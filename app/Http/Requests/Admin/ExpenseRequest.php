<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ExpenseRequest extends Request
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
            'exp_category_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'expense_date' => 'required',
            'payment_type' => 'required',
            'payment_date' => 'required',
            'image_name' => 'image|mimes:jpeg,jpg,png',
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
