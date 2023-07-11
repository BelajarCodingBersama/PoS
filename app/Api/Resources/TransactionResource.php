<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'sub_total' => $this->sub_total,
            'total' => $this->total,

            'user' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'user'),
                (new UserResource($this->user))
            ),
            'details' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'details'),
                (new TransactionDetailResourceCollection($this->details))
            )
        ];
    }
}