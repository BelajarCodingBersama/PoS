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
        $roles = $this->model;

        return $roles->get();
    }

    public function save(Role $role)
    {
        $role->save();

        return $role;
    }

}