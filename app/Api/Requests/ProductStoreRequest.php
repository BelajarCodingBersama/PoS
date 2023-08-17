<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|integer|gte:1',
            'amount' => 'required|integer|gte:1',
            'product_type_id' => 'required|exists:product_types,id',
            'file_id' => 'nullable|exists:files,id'
        ];
    }
}
