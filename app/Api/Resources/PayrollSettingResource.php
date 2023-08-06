<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollSettingResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nominal' => $this->nominal,

            'unit_type' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'unit_type'),
                (new UnitTypeResource($this->unitType))
            )
        ];
    }
}
