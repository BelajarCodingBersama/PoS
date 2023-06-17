<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|max:12',
            'role_id' => 'required|exists:roles,id',
            'file_id' => 'nullable|exists:files,id'
        ];
    }
}
