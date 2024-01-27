<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductPurchaseController;
use App\Http\Controllers\Api\ProductShipmentController;
use App\Http\Controllers\Api\ProductShipmentStatusController;
use App\Http\Controllers\Api\UserAddressController;
use App\Models\ProductShipment;
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
        Route::group(['prefix' => 'user'], function() {
            Route::get('/', [AuthController::class, 'user'])->middleware('auth:sanctum');

            // user addresses
            Route::apiResources([
                'addresses' => UserAddressController::class
            ]);
        });

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
                Route::group(['prefix' => 'shipments'], function () {
                    Route::get('/', [ProductShipmentController::class, 'index'])
                        ->can('index', [ProductShipment::class, 'purchase']);
                    Route::post('/', [ProductShipmentController::class, 'store'])
                        ->can('store', [ProductShipment::class, 'purchase']);

                    Route::group(['prefix' => '{shipment}'], function() {
                        // shipment statuses
                        Route::group(['prefix' => 'statuses'], function () {
                            Route::get('/', [ProductShipmentStatusController::class, 'index'])
                                ->can('index', [ProductShipmentStatus::class, 'purchase']);
                            Route::post('/', [ProductShipmentStatusController::class, 'store'])
                                ->can('store', [ProductShipmentStatus::class, 'purchase']);
                        });
                    });
                });
            });
            Route::post('/', [ProductPurchaseController::class, 'store']);
        });

});
