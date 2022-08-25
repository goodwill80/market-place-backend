<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::post('/logout', [UserController::class, 'logout']);
    // Route::get('/loginuser', [UserController::class, 'user']);
});

// PRODUCT CONTROLLER (PUBLIC ROUTES)
Route::controller(ProductController::class)->group(function() {
    Route::get('products/search/{name}', 'search');
    Route::get('/products', 'index');
    Route::get('products/{product}', 'show');
});

// USER CONTROLLER (PUBLIC ROUTES)
Route::controller(UserController::class)->group(function(){
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});


