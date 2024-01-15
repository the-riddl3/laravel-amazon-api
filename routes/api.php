<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['web']], function () {
    Route::post('/register', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        Auth::login($user);

        $request->session()->regenerate();

        return response()->json(['user' => $user]);
    })->name('register');

    Route::post('/login', function (Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json(['message' => 'Wrong credentials']);
        }

        $request->session()->regenerate();

        return response()->json(['user' => Auth::user()]);
    })->name('login');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        return response()->json(['message' => 'Logged out']);
    })->name('logout')->middleware(['auth:sanctum']);

    Route::group(['prefix' => 'products'], function () {
        Route::post('/', function (ProductStoreRequest $request) {
            $product = new Product($request->only(['name', 'description', 'price']));
            $product->seller()->associate(Auth::user());
            $product->save();

            return response()->json([
                'product' => $product,
                'message' => 'Product created successfully',
            ]);
        })->middleware(['auth:sanctum']);

        Route::get('/', function () {
            // recommendation system TODO
            return Product::all();
        });

        Route::get('/{product}', function (ProductRequest $product) {
            return $product;
        });

        Route::put('/{product}', function (Product $product, ProductStoreRequest $request) {
            Gate::authorize('store', $product);

            $product->update($request->only(['name', 'description', 'price']));

            return response()->json(['message' => 'product updated']);
        })->middleware(['auth:sanctum']);

        Route::delete('/{product}', function (Product $product, Request $request) {
            Gate::authorize('store', $product);

            $product->delete();

            return response()->json(['message' => 'Product has been deleted']);
        })->middleware(['auth:sanctum']);
    });
});
