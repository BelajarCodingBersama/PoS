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

    public function get()
    {
        $payrollSettings = $this->model;

        return $payrollSettings->get();
    }

    public function save(PayrollSetting $payrollSetting)
    {
        $payrollSetting->save();

        return $payrollSetting;
    }
}
