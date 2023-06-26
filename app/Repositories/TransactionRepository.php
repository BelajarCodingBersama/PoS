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
        $transactions = $this->model;

        return $transactions->get();
    }

    public function save(Transaction $transaction)
    {
        $transaction->save();

        return $transaction;
    }
}