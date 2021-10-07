<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ImportFilesRequest extends Request
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
            'import_files' => 'required|max:2048',
            'upload_type' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'import_files.max' => 'Can not upload a file greater than 2 MB',
        ];
    }
}
