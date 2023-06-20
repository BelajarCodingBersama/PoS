<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository {

    private $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $roles = $this->model
            ->when(!empty($params['search']['name']), function ($query) use ($params){
                return $query->where('name', 'LIKE', '%' . $params['search']['name'] . '%' );
            });

        if (!empty($params['paginate'])) {
            return $roles->paginate($params['paginate']);
        }

        return $roles->get();
    }

    public function save(Role $role)
    {
        $role->save();

        return $role;
    }

}