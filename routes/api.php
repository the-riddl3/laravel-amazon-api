<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::group(['middleware' => ['web']], function() {
    Route::post('/register', function(Request $request) {
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
    });

    Route::post('/login', function(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(!Auth::attempt($validated)) {
            return response()->json(['message' => 'Wrong credentials']);
        }

        $request->session()->regenerate();

        return response()->json(['user' => Auth::user()]);
    });

    Route::post('/logout', function(Request $request) {
        $user = $request->user();

        if(!$user) {
            return response()->json(['message' => 'You are not logged in']);
        }

        Auth::logout();

        return response()->json(['message' => 'Logged out']);
    });
});
