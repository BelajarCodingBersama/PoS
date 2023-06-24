<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($cart) use ($request) {
            return [
                'id' => $cart->id,
                'amount' => $cart->amount,

                'product' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'product'),
                    (new ProductResource($cart->product))
                )
            ];
        });

        return $data;
    }
}
