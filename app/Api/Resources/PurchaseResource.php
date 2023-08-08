<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'amount' => $this->amount,
            'price' => $this->price,

            'product' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'product'),
                (new ProductResource($this->product))
            ),

            'seller' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'seller'),
                (new SellerResource($this->seller))
            ),

            'user' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'user'),
                (new UserResource($this->user))
            )
        ];
    }
}
