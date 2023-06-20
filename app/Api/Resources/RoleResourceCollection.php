<?php

namespace App\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($role) use ($request) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug
            ];
        });

        return $data;

    }
}