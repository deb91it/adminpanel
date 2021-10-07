<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\Request;

class HolidaySettingsRequest extends Request
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
            'title'         => 'required|min:3|max:50',
            'from_date'     => 'required|date',
            'to_date'       => 'required|date',
        ];
    }
}
