<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionDetailResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($transactionDetail) use ($request) {
            return [
                'id' => $transactionDetail->id,
                'amount' => $transactionDetail->amount,
                'price' => $transactionDetail->price,

                'product' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'product'),
                    (new ProductResource($transactionDetail->product))
                )
            ];
        });

        return $data;
    }
}