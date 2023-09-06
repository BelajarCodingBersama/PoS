<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,

            'role' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'role'),
                (new RoleResource($this->role))
            ),

            'file' => $this->when(
                RequestHelper::doesQueryParamsHasValue($request->query('include'), 'file'),
                (new FileResource($this->file))
            )
        ];
    }
}
