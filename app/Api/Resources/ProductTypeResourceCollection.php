<?php

namespace App\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductTypeResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($productType) use ($request) {
            return [
                'id' => $productType->id,
                'name' => $productType->name,
                'slug' => $productType->slug
            ];
        });

        return $data;
    }
}
