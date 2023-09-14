<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Cart;
use App\Models\Payroll;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Role;
use App\Policies\CartPolicy;
use App\Policies\PayrollPolicy;
use App\Policies\ProductPolicy;
use App\Policies\PurchasePolicy;
use App\Policies\RolePolicy;
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
        Purchase::class => PurchasePolicy::class,
        Payroll::class => PayrollPolicy::class,
        Role::class => RolePolicy::class,
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
