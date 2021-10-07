<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class MailConfigurationRequest extends Request
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
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port'     => 'required',
            'mail_username'    => 'required',
            'mail_password'  => 'required',
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
