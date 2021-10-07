<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\Request;

class AttendanceListRequest extends Request
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
            'day_name'    => 'required',
            'in_time'     => 'required',
            'working_hours'     => 'required',
            'delay_time'     => 'required|numeric',
            'extream_delay_time'     => 'required|numeric',
            'break_time'     => 'required|numeric',
            'working_type'     => 'required|numeric',
            'attendence_head_id'     => 'required|numeric',

        ];
    }
}
