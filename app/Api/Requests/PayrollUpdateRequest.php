<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_date' => 'nullable|required_if:status,Paid|date|date_format:Y-m-d',
            'status' => 'required|in:Paid,Pending'
        ];
    }
}
