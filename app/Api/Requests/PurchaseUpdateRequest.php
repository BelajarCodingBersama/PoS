<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => 'required|date|date_format:Y-m-d',
            'amount' => 'required|integer|gte:1',
            'price' => 'required|integer',
            'product_id' => 'required|exists:products,id',
            'seller_id' => 'required|exists:sellers,id'
        ];
    }
}
