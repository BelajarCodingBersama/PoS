<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:sellers,name,'. $this->seller->id
        ];
    }
}
