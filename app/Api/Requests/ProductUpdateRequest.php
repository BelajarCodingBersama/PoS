<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name,'. $this->product->id,
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'amount' => 'required|integer',
            'product_type_id' => 'required|exists:product_types,id'
        ];
    }
}
