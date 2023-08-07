<?php

namespace App\Repositories;

use App\Models\PayrollSetting;

class PayrollSettingRepository
{
    private $model;

    public function __construct(PayrollSetting $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $payrollSettings = $this->model
            ->when(!empty($params['search']['name']), function ($query) use ($params) {
                return $query->where('name', 'LIKE', '%' . $params['search']['name'] . '%');
            })
            ->when(!empty($params['search']['unit_type_id']), function ($query) use ($params) {
                return $query->where('unit_type_id', $params['search']['unit_type_id']);
            });

        if (!empty($params['paginate'])) {
            return $payrollSettings->paginate($params['paginate']);
        }

        return $payrollSettings->get();
    }

    public function save(PayrollSetting $payrollSetting)
    {
        $payrollSetting->save();

        return $payrollSetting;
    }
}
