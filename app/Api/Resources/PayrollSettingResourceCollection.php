<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PayrollSettingResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($payrollSetting) use ($request) {
            return [
                'id' => $payrollSetting->id,
                'name' => $payrollSetting->name,
                'nominal' => $payrollSetting->nominal,

                'unit_type' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'unit_type'),
                    (new UnitTypeResource($payrollSetting->unitType))
                )
            ];
        });

        return $data;
    }
}
