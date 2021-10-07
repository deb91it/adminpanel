<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PermissionRequest extends Request
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
            'permission_slug'   => 'required|min:3|max:150',
            'display_name'      => 'required|min:3|max:150',
            'permission_group'  => 'required',
        ];
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'permission_group.required' => 'Please select at-least one group name',
        ];
    }
}
