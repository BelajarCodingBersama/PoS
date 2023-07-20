<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseTypeStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:expense_types,name'
        ];
    }
}
