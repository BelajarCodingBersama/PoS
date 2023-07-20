<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role_id' => 'required|exists:roles,id',
            'amount' => 'required|integer'
        ];
    }
}
