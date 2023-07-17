<?php

namespace App\Repositories;

use App\Models\Purchase;

class PurchaseRepository
{
    private $model;

    public function __construct(Purchase $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $purchases = $this->model;

        if (!empty($params['paginate'])) {
            return $purchases->paginate($params['paginate']);
        }

        return $purchases->get();
    }

    public function save(Purchase $purchase)
    {
        $purchase->save();

        return $purchase;
    }
}
