<?php

namespace App\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SellerResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($seller) use ($request) {
            return [
                'id' => $seller->id,
                'name' => $seller->name,
                'slug' => $seller->slug
            ];
        });

        return $data;
    }
}
