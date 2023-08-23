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
        $payrolls = $this->model
            ->when(!empty($params['search']['name']), function ($query) use ($params) {
                return $query->whereHas('user', function ($query) use ($params) {
                    return $query->where('username', 'LIKE', '%' . $params['search']['name'] . '%');
                });
            })
            ->when(!empty($params['search']['status']), function ($query) use ($params) {
                return $query->where('status', 'LIKE', '%' . $params['search']['status'] . '%');
            })
            ->when(!empty($params['search']['month']), function ($query) use ($params) {
                return $query->whereDate('created_at', 'LIKE', '%' . $params['search']['month'] . '%');
            })
            ->when(!empty($params['search']['year']), function ($query) use ($params) {
                return $query->whereDate('created_at', 'LIKE', '%' . $params['search']['year'] . '%');
            });

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
