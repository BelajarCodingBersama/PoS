<?php

namespace App\Repositories;

use App\Models\Payroll;

class PayrollRepository
{
    private $model;

    public function __construct(Payroll $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $payrolls = $this->model;

        if (!empty($params['paginate'])) {
            return $payrolls->paginate($params['paginate']);
        }

        return $payrolls->get();
    }

    public function save(Payroll $payroll)
    {
        $payroll->save();

        return $payroll;
    }
}
