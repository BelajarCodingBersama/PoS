<?php

namespace App\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UnitTypeResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($unitType) use ($request) {
            return [
                'id' => $unitType->id,
                'name' => $unitType->name
            ];
        });

        return $data;
    }
}
