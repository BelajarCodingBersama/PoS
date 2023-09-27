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
            ->when(!empty($params['select']), function ($query) use ($params) {
                return $query->selectRaw($params['select']);
            })
            ->when(!empty($params['search']['name']), function ($query) use ($params) {
                return $query->whereHas('user', function ($query) use ($params) {
                    return $query->where('username', 'LIKE', '%' . $params['search']['name'] . '%');
                });
            })
            ->when(!empty($params['search']['status']), function ($query) use ($params) {
                return $query->where('status', 'LIKE', '%' . $params['search']['status'] . '%');
            })
            ->when(!empty($params['search']['month']), function ($query) use ($params) {
                return $query->whereMonth('created_at', $params['search']['month']);
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
            return $payrolls->sum($params['sum']);
        }

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
