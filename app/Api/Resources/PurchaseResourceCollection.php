<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PurchaseResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($purchase) use ($request) {
            return [
                'id' => $purchase->id,
                'date' => $purchase->date,
                'amount' => $purchase->amount,
                'price' => $purchase->price,

                'product' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'product'),
                    (new ProductResource($purchase->product))
                ),

                'seller' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'seller'),
                    (new SellerResource($purchase->seller))
                ),

                'user' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'user'),
                    (new UserResource($purchase->user))
                )
            ];
        });

        return $data;
    }
}
