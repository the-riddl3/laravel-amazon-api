<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPurchaseController;
use App\Http\Controllers\ProductShipmentStatusController;
use App\Models\ProductShipmentStatus;
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

Route::group(['middleware' => ['web']], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

        Route::post('/register', [AuthController::class, 'register'])->name('register');

        Route::post('/login', [AuthController::class, 'login'])->name('login');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->middleware(['auth:sanctum'])
            ->name('logout');
    });

    Route::apiResources([
        'products' => ProductController::class,
        'categories' => CategoryController::class,
    ]);

    Route::middleware(['auth:sanctum'])
        ->prefix('product-purchases')
        ->group(function () {
            Route::prefix('/{purchase}')->group(function () {
                Route::get('/', [ProductPurchaseController::class, 'show'])
                    ->can('show', 'purchase');

                // shipping feature
                Route::group(['prefix' => 'shipment-statuses'], function () {
                    Route::get('/', [ProductShipmentStatusController::class, 'index'])
                        ->can('index', [ProductShipmentStatus::class, 'purchase']);
                    Route::post('/', [ProductShipmentStatusController::class, 'store'])
                        ->can('store', [ProductShipmentStatus::class, 'purchase']);
                });
            });
            Route::post('/', [ProductPurchaseController::class, 'store']);
        });

});
