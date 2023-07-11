<?php

namespace App\Repositories;

use App\Models\Seller;

class SellerRepository
{
    private $model;

    public function __construct(Seller $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $sellers = $this->model
            ->when(!empty($params['search']['name']), function ($query) use ($params) {
                return $query->where('name', 'LIKE', '%' . $params['search']['name'] . '%');
            });

        if (!empty($params['paginate'])) {
            return $sellers->paginate($params['paginate']);
        }

        return $sellers->get();
    }

    public function save(Seller $seller)
    {
        $seller->save();

        return $seller;
    }
}
