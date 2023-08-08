<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PayrollResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($payroll) use ($request) {
            return [
                'id' => $payroll->id,
                'role' => $payroll->role,
                'basic_salary' => $payroll->basic_salary,
                'allowances' => $payroll->allowances,
                'tax' => $payroll->tax,
                'net_pay' => $payroll->net_pay,
                'payment_date' => $payroll->payment_date,
                'status' => $payroll->status,

                'user' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'user'),
                    (new UserResource($payroll->user))
                )
            ];
        });

        return $data;
    }
}
