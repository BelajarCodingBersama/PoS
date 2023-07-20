<?php

namespace App\Repositories;

use App\Models\ExpenseType;

class ExpenseTypeRepository
{
    private $model;

    public function __construct(ExpenseType $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $expenseTypes = $this->model
            ->when(!empty($params['search']['name']), function ($query) use ($params) {
                return $query->where('name', 'LIKE', '%' . $params['search']['name'] . '%');
            });
        
        if (!empty($params['paginate'])) {
            return $expenseTypes->paginate($params['paginate']);
        }

        return $expenseTypes->get();
    }

    public function save(ExpenseType $expenseType)
    {
        $expenseType->save();

        return $expenseType;
    }
}