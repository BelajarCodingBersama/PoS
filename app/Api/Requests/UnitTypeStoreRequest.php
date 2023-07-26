<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitTypeStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:unit_types,name'
        ];
    }
}
