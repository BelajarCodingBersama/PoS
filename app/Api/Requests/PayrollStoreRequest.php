<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'payment_date' => 'nullable|required_if:status,Paid|date|date_format:Y-m-d',
            'status' => 'required|in:Paid,Pending'
        ];
    }
}
