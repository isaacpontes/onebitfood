<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\AvailableCitiesController;
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

Route::apiResource('/categories', CategoryController::class)->only(['index']);
Route::apiResource('/restaurants', RestaurantController::class)->only(['index', 'show']);
Route::apiResource('/orders', OrderController::class)->only(['store', 'show']);
Route::get('/available-cities', [AvailableCitiesController::class, 'index'])->name('available-cities');
