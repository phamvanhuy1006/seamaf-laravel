<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('lastest', [ProductController::class, 'lastestProducts']);
    Route::get('search', [ProductController::class, 'search']);
    Route::get('top-selling', [ProductController::class, 'topSellProducts']);
    Route::get('relate-products', [ProductController::class, 'relateProducts']);
    Route::post('/', [ProductController::class, 'store'])->middleware('auth:api');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->middleware('auth:api');
    Route::get('/{product}', [ProductController::class, 'show']);
    Route::get('/ontop/{product}', [ProductController::class, 'onTop']);
});
Route::group([
    // 'middleware' => 'auth:api'
  ], function () {
    Route::prefix('cart-user')->group(function () {
        Route::get('/', [CartController::class, 'list']);
        Route::get('/add', [CartController::class, 'store']);
        Route::get('/update-quality', [CartController::class, 'updateQuality']);
        Route::delete('/{cart}', [CartController::class, 'destroy']);

    });
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
});

Route::post('login', [LoginController::class, 'login']);

Route::group([
  'middleware' => 'auth:api'
], function () {
    Route::get('logout', [LoginController::class, 'logout']);
});