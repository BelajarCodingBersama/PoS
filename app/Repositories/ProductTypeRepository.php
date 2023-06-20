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
        $productTypes = $this->model
            ->when(!empty($params['search']['name']), function ($query) use ($params) {
                return $query->where('name', 'LIKE', '%' . $params['search']['name'] . '%');
            });

        if (!empty($params['paginate'])) {
            return $productTypes->paginate($params['paginate']);
        }

        return $productTypes->get();
    }

    public function save(ProductType $productType)
    {
        $productType->save();

        return $productType;
    }
}
