<?php

namespace App\Repositories;

use App\Models\TransactionDetail;

class TransactionDetailRepository
{
    private $model;

    public function __construct(TransactionDetail $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
       $transactionDetails = $this->model;
       
       return $transactionDetails;
    }

    public function save(TransactionDetail $transactionDetail)
    {
        $transactionDetail->save();

        return $transactionDetail;
    }
}