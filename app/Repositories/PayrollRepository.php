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

    public function get()
    {
        $payrolls = $this->model;

        return $payrolls->get();
    }

    public function save(Payroll $payroll)
    {
        $payroll->save();

        return $payroll;
    }
}
