<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'basic_salary' => $this->basic_salary,
            'allowances' => $this->allowances,
            'tax' => $this->tax,
            'net_pay' => $this->net_pay,
            'payment_date' => $this->payment_date,
            'status' => $this->status,

            'user' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'user'),
                (new UserResource($this->user))
            )
        ];
    }
}
