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

    public function get()
    {
        $purchases = $this->model;

        return $purchases->get();
    }

    public function save(Purchase $purchase)
    {
        $purchase->save();

        return $purchase;
    }
}
