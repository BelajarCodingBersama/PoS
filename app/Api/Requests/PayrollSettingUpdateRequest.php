<?php

namespace App\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollSettingUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:payroll_settings,name,'. $this->payrollSetting->id,
            'nominal' => 'required|integer',
            'unit_type_id' => 'required|exists:unit_types,id'
        ];
    }
}
