<?php

namespace App\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseTypeResourceCollection extends ResourceCollection
{
    public function toArray(Request $request)
    {
        $data = [];
        $data = $this->collection->transform(function ($expenseType) use ($request) {
            return [
                'id' => $expenseType->id,
                'name' => $expenseType->name,
                'slug' => $expenseType->slug
            ];
        });

        return $data;
    }
}
