<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SalaryResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($salary) use ($request) {
            return [
                'id' => $salary->id,
                'amount' => $salary->amount,

                'role' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'role'),
                    (new RoleResource($salary->role))
                )
            ];
        });

        return $data;
    }
}