<?php

namespace App\Repositories;

use App\Models\Salary;

class SalaryRepository
{
    private $model;

    public function __construct(Salary $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $salaries = $this->model;
        
        if (!empty($params['paginate'])) {
            return $salaries->paginate($params['paginate']);
        }

        return $salaries->get();
    }

    public function save(Salary $salary)
    {
        $salary->save();

        return $salary;
    }
}