<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository {

    private $model;

    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $transactions = $this->model
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
            return $transactions->sum($params['sum']);
        }

        if (!empty($params['paginate'])) {
            return $transactions->paginate($params['paginate']);
        }

        return $transactions->get();
    }

    public function save(Transaction $transaction)
    {
        $transaction->save();

        return $transaction;
    }
}
