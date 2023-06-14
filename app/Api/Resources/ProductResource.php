<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'amount' => $this->amount,

            'product_type' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'product_type'),
                (new ProductTypeResource($this->productType))
            )
        ];
    }
}
