<?php

use App\Api\Controllers\Admin\AdminDashboardController;
use App\Api\Controllers\Admin\AdminExpenseTypeController;
use App\Api\Controllers\Admin\AdminFileController;
use App\Api\Controllers\Admin\AdminPayrollSettingController;
use App\Api\Controllers\Admin\AdminProductController;
use App\Api\Controllers\Admin\AdminProductTypeController;
use App\Api\Controllers\Admin\AdminUserController;
use App\Api\Controllers\Admin\AdminRoleController;
use App\Api\Controllers\Admin\AdminSalaryController;
use App\Api\Controllers\Admin\AdminTransactionController;
use App\Api\Controllers\Admin\AdminSellerController;
use App\Api\Controllers\Admin\AdminUnitTypeController;
use App\Api\Controllers\AuthController;
use App\Api\Controllers\Cashier\CashierCartController;
use App\Api\Controllers\Cashier\CashierTransactionController;
use App\Api\Controllers\Finance\FinanceDashboardController;
use App\Api\Controllers\Finance\FinancePayrollController;
use App\Api\Controllers\Finance\FinancePurchaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('admin')->middleware('auth:sanctum', 'ability:admin,finance,cashier')->group(function () {
    Route::get('products', [AdminProductController::class, 'index']);
});

Route::prefix('admin')->middleware('auth:sanctum', 'ability:admin,finance')->group(function () {
    Route::get('sellers', [AdminSellerController::class, 'index']);
    Route::get('users', [AdminUserController::class, 'index']);
});

Route::prefix('admin')->middleware('auth:sanctum', 'ability:admin,cashier')->group(function () {
    Route::get('product-types', [AdminProductTypeController::class, 'index']);
    Route::get('products/{product}/show', [AdminProductController::class, 'show']);
});

Route::prefix('admin')->middleware('auth:sanctum', 'abilities:admin')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index']);

    Route::prefix('product-types')->controller(AdminProductTypeController::class)->group(function () {
        Route::post('store', 'store');
        Route::get('{productType}/show', 'show');
        Route::patch('{productType}/update', 'update');
        Route::delete('{productType}/delete', 'destroy');
    });

    Route::prefix('products')->controller(AdminProductController::class)->group(function () {
        Route::post('store', 'store');
        Route::patch('{product}/update', 'update');
        Route::delete('{product}/delete', 'destroy');
    });

    Route::prefix('users')->controller(AdminUserController::class)->group(function () {
        Route::post('store', 'store');
        Route::get('{user}/show', 'show');
        Route::patch('{user}/update', 'update');
    });

    Route::prefix('roles')->controller(AdminRoleController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('store', 'store');
        Route::get('{role}/show', 'show');
        Route::patch('{role}/update', 'update');
        Route::delete('{role}/delete', 'destroy');
    });

    Route::post('files/store', [AdminFileController::class, 'store']);

    Route::prefix('transactions')->controller(AdminTransactionController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('{transaction}/show', 'show');
        Route::get('{transaction}/export', 'export');
    });

    Route::prefix('sellers')->controller(AdminSellerController::class)->group(function () {
        Route::post('store', 'store');
        Route::get('{seller}/show', 'show');
        Route::patch('{seller}/update', 'update');
        Route::delete('{seller}/delete', 'destroy');
    });

    Route::prefix('salaries')->controller(AdminSalaryController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('store', 'store');
        Route::get('{salary}/show', 'show');
        Route::patch('{salary}/update', 'update');
        Route::delete('{salary}/delete', 'destroy');
    });

    Route::prefix('expense-types')->controller(AdminExpenseTypeController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('store', 'store');
        Route::get('{expenseType}/show', 'show');
        Route::patch('{expenseType}/update', 'update');
        Route::delete('{expenseType}/delete', 'destroy');
    });

    Route::prefix('unit-types')->controller(AdminUnitTypeController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('store', 'store');
        Route::get('{unitType}/show', 'show');
        Route::patch('{unitType}/update', 'update');
        Route::delete('{unitType}/delete', 'destroy');
    });

    Route::prefix('payroll-settings')->controller(AdminPayrollSettingController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('store', 'store');
        Route::get('{payrollSetting}/show', 'show');
        Route::patch('{payrollSetting}/update', 'update');
        Route::delete('{payrollSetting}/delete', 'destroy');
    });
});

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');

    Route::middleware('auth:sanctum', 'ability:admin,cashier,finance')->group(function () {
        Route::get('show', 'show');
        Route::patch('change-password', 'changePassword');
        Route::post('logout', 'logout');
    });
});

Route::prefix('cashier')->middleware('auth:sanctum', 'abilities:cashier')->group(function () {
    Route::prefix('carts')->controller(CashierCartController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('store', 'store');
        Route::get('{cart}/show', 'show');
        Route::patch('{cart}/update', 'update');
        Route::delete('{cart}/delete', 'destroy');
    });

    Route::post('transactions/store', [CashierTransactionController::class, 'store']);
});

Route::prefix('finance')->middleware('auth:sanctum', 'abilities:finance')->group(function () {
    Route::get('dashboard', [FinanceDashboardController::class, 'index']);

    Route::prefix('purchases')->controller(FinancePurchaseController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('store', 'store');
        Route::get('{purchase}/show', 'show');
        Route::patch('{purchase}/update', 'update');
        Route::delete('{purchase}/delete', 'destroy');
        Route::post('import', 'import');
        Route::get('download-template', 'download');
    });

    Route::prefix('payrolls')->controller(FinancePayrollController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('store', 'store');
        Route::get('{payroll}/show', 'show');
        Route::patch('{payroll}/update', 'update');
        Route::get('report', 'print');
        Route::post('import', 'import');
        Route::get('download-template', 'download');
    });
});
