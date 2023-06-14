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
        $products = $this->model;

        return $products->get();
    }

    public function save(Product $product)
    {
        $product->save();

        return $product;
    }
}
