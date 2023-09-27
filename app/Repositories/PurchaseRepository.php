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
        $purchases = $this->model
            ->when(!empty($params['select']), function ($query) use ($params) {
                return $query->selectRaw($params['select']);
            })
            ->when(!empty($params['search']['year']), function ($query) use ($params) {
                return $query->whereYear('created_at', $params['search']['year']);
            })
            ->when(!empty($params['group']), function ($query) use ($params) {
                return $query->groupByRaw($params['group']);
            })
            ->when(!empty($params['order']), function ($query) use ($params) {
                return $query->orderByRaw($params['order']);
            });

        if (!empty($params['sum'])) {
            return $purchases->sum($params['sum']);
        }

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
