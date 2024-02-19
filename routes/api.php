<?php

use App\Http\Controllers\API\OrdersController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!

sanctum - passport
|
*/

// not auth requests
Route::middleware('guest')->group(function () {
    //Get all user
    Route::get('users', [UserController::class, 'getAllUsers']);

    // 1) Register
    Route::post('register', [RegisteredUserController::class, 'store']);

    // 2) Login
    Route::put('login', [AuthenticatedSessionController::class, 'store']);

    // 3) Get Products
    Route::get('products', [ProductsController::class, 'GetProducts']);

    // 4) Get Categories
    Route::get('categories', [ProductsController::class, 'GetCategories']);

    Route::post('categories', [ProductsController::class, 'createCategories']);

    Route::delete('categories', [ProductsController::class, 'destroyCategory']);
    //add
    Route::post('addProduct', [ProductsController::class, 'store']);
});

// auth requests
Route::middleware('auth:sanctum')->group(function () {
    // 1) Create orders
    Route::post('orders', [OrdersController::class, 'store']);

    // 2) Get User API Requests
    Route::get('user', function (Request $request) {
        return $request->user();
    });


    // 3) Get orders
    Route::get('orders', [OrdersController::class, 'index']);

    // 4) Modify/Update Order
    Route::put('orders/{order}', [OrdersController::class, 'update']);

    // 5) Logout User
    Route::put('logout', [AuthenticatedSessionController::class, 'logout']);

    Route::delete('users/{id}', [UserController::class, 'destroy']);



});
