<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,

            'product' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'product'),
                (new ProductResource($this->product))
            )
        ];
    }
}
