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

    public function get($params = [])
    {
        $unitTypes = $this->model
            ->when(!empty($params['search']['name']), function ($query) use ($params){
                return $query->where('name', 'LIKE', '%' . $params['search']['name'] . '%' );
            });

        if (!empty($params['paginate'])) {
            return $unitTypes->paginate($params['paginate']);
        }

        return $unitTypes->get();
    }

    public function save(UnitType $unitType)
    {
        $unitType->save();

        return $unitType;
    }
}
