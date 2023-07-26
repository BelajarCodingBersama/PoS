<?php

namespace App\Repositories;

use App\Models\UnitType;

class UnitTypeRepository
{
    private $model;

    public function __construct(UnitType $model)
    {
        $this->model = $model;
    }

    public function get()
    {
        $unitTypes = $this->model;

        return $unitTypes->get();
    }

    public function save(UnitType $unitType)
    {
        $unitType->save();

        return $unitType;
    }
}
