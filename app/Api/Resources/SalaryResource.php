<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'nominal' => $this->nominal,

            'role' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'role'),
                (new RoleResource($this->role))
            )
        ];
    }
}