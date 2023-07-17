<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Cart;
use App\Models\Purchase;
use App\Policies\CartPolicy;
use App\Policies\PurchasePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Cart::class => CartPolicy::class,
        Purchase::class => PurchasePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
