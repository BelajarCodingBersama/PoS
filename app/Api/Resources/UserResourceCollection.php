<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($user) use ($request) {
            return [
                'id' => $user->id,
                'username' => $user->username,

                'role' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'role'),
                    (new RoleResource($user->role))
                ),

                'file' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'file'),
                    (new FileResource($user->file))
                )
            ];
        });

        return $data;
    }
}
