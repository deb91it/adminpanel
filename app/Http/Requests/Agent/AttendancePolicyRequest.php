<?php

namespace App\Http\Requests\Agent;

use App\Http\Requests\Request;

class AttendancePolicyRequest extends Request
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
            'day_name.*'    => 'required',
            'in_time'     => 'required',
            'in_time.*'     => 'required',
            'working_hours'     => 'required',
            'working_hours.*'     => 'required',
            'delay_time'     => 'required',
            'delay_time.*'     => 'required',
            'extream_delay_time'     => 'required',
            'extream_delay_time.*'     => 'required',
            'break_time'     => 'required',
            'break_time.*'     => 'required',
            'working_type'     => 'required',
            'working_type.*'     => 'required',
            'attendence_head_name'     => 'required',
            'effective_from'     => 'required',

        ];
    }
}
