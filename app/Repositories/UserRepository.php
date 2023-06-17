<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function get($params = [])
    {
        $users = $this->model;

        return $users->get();
    }

    public function save(User $user)
    {
        $user->save();

        return $user;
    }
}
