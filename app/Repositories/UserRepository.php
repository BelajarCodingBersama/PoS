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
        $users = $this->model
            ->when(!empty($params['search']['username']), function ($query) use ($params) {
                return $query->where('username', 'LIKE', '%' . $params['search']['username'] . '%');
            })
            ->when(!empty($params['search']['role_id']), function ($query) use ($params) {
                return $query->where('role_id', $params['search']['role_id']);
            });

        if (!empty($params['paginate'])) {
            return $users->paginate($params['paginate']);
        }

        return $users->get();
    }

    public function save(User $user)
    {
        $user->save();

        return $user;
    }
}
