<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($transaction) use ($request) {
            return [
                'id' => $transaction->id,
                'sub_total' => $transaction->sub_total,
                'total' => $transaction->total,

                'user' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'user'),
                    (new UserResource($transaction->user))
                ),
                'details' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'details'),
                    (new TransactionDetailResourceCollection($transaction->details))
                )
            ];
        });

        return $data;
    }
}