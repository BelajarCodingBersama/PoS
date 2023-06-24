<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository
{
    private $model;

    public function __construct(Cart $model)
    {
        $this->model = $model;
    }

    public function get()
    {
        $carts = $this->model;

        return $carts->get();
    }

    public function save(Cart $cart)
    {
        $cart->save();

        return $cart;
    }
}
