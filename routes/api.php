<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\AvailableCitiesController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/restaurants', RestaurantController::class)->except(['update']);
Route::apiResource('/product-categories', ProductCategoryController::class)->only(['store', 'show']);
Route::apiResource('/products', ProductController::class)->only(['store', 'show']);
Route::apiResource('/orders', OrderController::class)->only(['store', 'show', 'destroy']);
Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
Route::get('/available-cities', [AvailableCitiesController::class, 'index'])->name('available-cities');
