<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password' => 'required|string|min:6|max:12|current_password',
            'new_password' => 'required|string|min:6|max:12|confirmed',
            'new_password_confirmation' => 'required|string|min:6|max:12',
        ];
    }
}
