<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'folder_name' => 'required|string|max:255',
            'files' => 'required|array',
            'files.*' => 'image'
        ];
    }
}
