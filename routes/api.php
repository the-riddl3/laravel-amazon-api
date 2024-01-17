<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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
    Route::group(['prefix' => 'auth'], function() {
        Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

        Route::post('/register', [AuthController::class, 'register'])->name('register');

        Route::post('/login', [AuthController::class, 'login'] )->name('login');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->middleware(['auth:sanctum'])
            ->name('logout');
    });

    Route::apiResources([
        'products' => ProductController::class,
    ]);
});
