<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:255|exists:users,username',
            'password' => 'required|string|min:6|max:12'
        ];
    }
}
