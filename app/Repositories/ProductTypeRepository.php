<?php

namespace App\Repositories;

use App\Models\ProductType;

class ProductTypeRepository
{
    private $model;

    public function __construct(ProductType $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $productTypes = $this->model;

        return $productTypes->get();
    }

    public function save(ProductType $productType)
    {
        $productType->save();

        return $productType;
    }
}
