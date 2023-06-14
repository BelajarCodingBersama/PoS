<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductTypeStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:product_types,name'
        ];
    }
}
