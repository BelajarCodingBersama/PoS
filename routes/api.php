<?php

use App\Api\Controllers\Admin\AdminAuthController;
use App\Api\Controllers\Admin\AdminProductController;
use App\Api\Controllers\Admin\AdminProductTypeController;
use App\Api\Controllers\Admin\AdminUserController;
use App\Api\Controllers\Admin\AdminRoleController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware('auth:sanctum', 'abilities:admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);

        Route::prefix('product-types')->group(function () {
            Route::get('/', [AdminProductTypeController::class, 'index']);
            Route::post('store', [AdminProductTypeController::class, 'store']);
            Route::get('{productType}/show', [AdminProductTypeController::class, 'show']);
            Route::patch('{productType}/update', [AdminProductTypeController::class, 'update']);
            Route::delete('{productType}/delete', [AdminProductTypeController::class, 'destroy']);
        });

        Route::prefix('products')->group(function () {
            Route::get('/', [AdminProductController::class, 'index']);
            Route::post('store', [AdminProductController::class, 'store']);
            Route::get('{product}/show', [AdminProductController::class, 'show']);
            Route::patch('{product}/update', [AdminProductController::class, 'update']);
            Route::delete('{product}/delete', [AdminProductController::class, 'destroy']);
        });

        Route::prefix('users')->group(function () {
            Route::get('/', [AdminUserController::class, 'index']);
            Route::post('store', [AdminUserController::class, 'store']);
            Route::patch('{user}/update', [AdminUserController::class, 'update']);
        });

        Route::prefix('roles')->group(function() {
            Route::get('/', [AdminRoleController::class, 'index']);
            Route::post('store', [AdminRoleController::class, 'store']);
            Route::patch('{role}/update', [AdminRoleController::class, 'update']);
            Route::delete('{role}/delete', [AdminRoleController::class, 'destroy']);
        });
    });
});
