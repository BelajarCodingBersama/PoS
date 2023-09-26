<?php

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
    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index']);
    });
});

Route::prefix('admin')->middleware('auth:sanctum', 'ability:admin,finance')->group(function () {
    Route::prefix('sellers')->group(function () {
        Route::get('/', [AdminSellerController::class, 'index']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index']);
    });
});

Route::prefix('admin')->middleware('auth:sanctum', 'ability:admin,cashier')->group(function () {
    Route::prefix('product-types')->group(function () {
        Route::get('/', [AdminProductTypeController::class, 'index']);
    });

    Route::prefix('products')->group(function () {
        Route::get('{product}/show', [AdminProductController::class, 'show']);
    });
});

Route::prefix('admin')->middleware('auth:sanctum', 'abilities:admin')->group(function () {
    Route::prefix('product-types')->group(function () {
        Route::post('store', [AdminProductTypeController::class, 'store']);
        Route::get('{productType}/show', [AdminProductTypeController::class, 'show']);
        Route::patch('{productType}/update', [AdminProductTypeController::class, 'update']);
        Route::delete('{productType}/delete', [AdminProductTypeController::class, 'destroy']);
    });

    Route::prefix('products')->group(function () {
        Route::post('store', [AdminProductController::class, 'store']);
        Route::patch('{product}/update', [AdminProductController::class, 'update']);
        Route::delete('{product}/delete', [AdminProductController::class, 'destroy']);
    });

    Route::prefix('users')->group(function () {
        Route::post('store', [AdminUserController::class, 'store']);
        Route::get('{user}/show', [AdminUserController::class, 'show']);
        Route::patch('{user}/update', [AdminUserController::class, 'update']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [AdminRoleController::class, 'index']);
        Route::post('store', [AdminRoleController::class, 'store']);
        Route::get('{role}/show', [AdminRoleController::class, 'show']);
        Route::patch('{role}/update', [AdminRoleController::class, 'update']);
        Route::delete('{role}/delete', [AdminRoleController::class, 'destroy']);
    });

    Route::prefix('files')->group(function () {
        Route::post('store', [AdminFileController::class, 'store']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [AdminTransactionController::class, 'index']);
        Route::get('{transaction}/show', [AdminTransactionController::class, 'show']);
    });

    Route::prefix('sellers')->group(function () {
        Route::post('store', [AdminSellerController::class, 'store']);
        Route::get('{seller}/show', [AdminSellerController::class, 'show']);
        Route::patch('{seller}/update', [AdminSellerController::class, 'update']);
        Route::delete('{seller}/delete', [AdminSellerController::class, 'destroy']);
    });

    Route::prefix('salaries')->group(function () {
        Route::get('/', [AdminSalaryController::class, 'index']);
        Route::post('store', [AdminSalaryController::class, 'store']);
        Route::get('{salary}/show', [AdminSalaryController::class, 'show']);
        Route::patch('{salary}/update', [AdminSalaryController::class, 'update']);
        Route::delete('{salary}/delete', [AdminSalaryController::class, 'destroy']);
    });

    Route::prefix('expense-types')->group(function () {
        Route::get('/', [AdminExpenseTypeController::class, 'index']);
        Route::post('store', [AdminExpenseTypeController::class, 'store']);
        Route::get('{expenseType}/show', [AdminExpenseTypeController::class, 'show']);
        Route::patch('{expenseType}/update', [AdminExpenseTypeController::class, 'update']);
        Route::delete('{expenseType}/delete', [AdminExpenseTypeController::class, 'destroy']);
    });

    Route::prefix('unit-types')->group(function () {
        Route::get('/', [AdminUnitTypeController::class, 'index']);
        Route::post('store', [AdminUnitTypeController::class, 'store']);
        Route::get('{unitType}/show', [AdminUnitTypeController::class, 'show']);
        Route::patch('{unitType}/update', [AdminUnitTypeController::class, 'update']);
        Route::delete('{unitType}/delete', [AdminUnitTypeController::class, 'destroy']);
    });

    Route::prefix('payroll-settings')->group(function () {
        Route::get('/', [AdminPayrollSettingController::class, 'index']);
        Route::post('store', [AdminPayrollSettingController::class, 'store']);
        Route::get('{payrollSetting}/show', [AdminPayrollSettingController::class, 'show']);
        Route::patch('{payrollSetting}/update', [AdminPayrollSettingController::class, 'update']);
        Route::delete('{payrollSetting}/delete', [AdminPayrollSettingController::class, 'destroy']);
    });
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum', 'ability:admin,cashier,finance')->group(function () {
        Route::get('show', [AuthController::class, 'show']);
        Route::patch('change-password', [AuthController::class, 'changePassword']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('cashier')->middleware('auth:sanctum', 'abilities:cashier')->group(function () {
    Route::prefix('carts')->group(function () {
        Route::get('/', [CashierCartController::class, 'index']);
        Route::post('store', [CashierCartController::class, 'store']);
        Route::get('{cart}/show', [CashierCartController::class, 'show']);
        Route::patch('{cart}/update', [CashierCartController::class, 'update']);
        Route::delete('{cart}/delete', [CashierCartController::class, 'destroy']);
    });

    Route::prefix('transactions')->group(function() {
        Route::post('store', [CashierTransactionController::class, 'store']);
    });
});

Route::prefix('finance')->middleware('auth:sanctum', 'abilities:finance')->group(function () {
    Route::prefix('purchases')->group(function () {
        Route::get('/', [FinancePurchaseController::class, 'index']);
        Route::post('store', [FinancePurchaseController::class, 'store']);
        Route::get('{purchase}/show', [FinancePurchaseController::class, 'show']);
        Route::patch('{purchase}/update', [FinancePurchaseController::class, 'update']);
        Route::delete('{purchase}/delete', [FinancePurchaseController::class, 'destroy']);
    });

    Route::prefix('payrolls')->group(function () {
        Route::get('/', [FinancePayrollController::class, 'index']);
        Route::post('store', [FinancePayrollController::class, 'store']);
        Route::get('{payroll}/show', [FinancePayrollController::class, 'show']);
        Route::patch('{payroll}/update', [FinancePayrollController::class, 'update']);
        Route::get('report', [FinancePayrollController::class, 'print']);
    });
});
