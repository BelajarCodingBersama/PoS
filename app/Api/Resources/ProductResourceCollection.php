<?php

namespace App\Api\Resources;

use App\Helpers\RequestHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($product) use ($request) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => $product->price,
                'amount' => $product->amount,

                'product_type' => $this->when(
                    RequestHelper::doesQueryParamsHasValue($request->query('include'), 'product_type'),
                    (new ProductTypeResource($product->productType))
                )
            ];
        });

        return $data;
    }
}
