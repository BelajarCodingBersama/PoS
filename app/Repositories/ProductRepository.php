<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    private $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $products = $this->model
            ->when(!empty($params['search']['name']), function ($query) use ($params) {
                return $query->where('name', 'LIKE', '%' . $params['search']['name'] . '%');
            })
            ->when(!empty($params['search']['product_type_id']), function ($query) use ($params) {
                return $query->where('product_type_id', $params['search']['product_type_id']);
            });

        if (!empty($params['paginate'])) {
            return $products->paginate($params['paginate']);
        }

        return $products->get();
    }

    public function save(Product $product)
    {
        $product->save();

        return $product;
    }
}
