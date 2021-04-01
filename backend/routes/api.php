<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;

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

Route::prefix('/srand')->group(
    function () {
        Route::get('/fillUserDatabase', [UserController::class, 'fillUserDatabase']);
        Route::get('/fillCarDatabase', [CarController::class, 'fillCarDatabase']);
    }
);

Route::get('/users', [UserController::class, 'index']);

Route::prefix('/user')->group(
    function () {
        Route::post('/store', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    }
);

Route::get('/cars', [CarController::class, 'index']);

Route::prefix('/car')->group(
    function () {
        Route::post('/store', [CarController::class, 'store']);
        Route::get('/{id}', [CarController::class, 'show']);
        Route::put('/{id}', [CarController::class, 'update']);
        Route::delete('/{id}', [CarController::class, 'destroy']);
    }
);

Route::get('/reservations', [CarController::class, 'indexReservation']);

Route::prefix('/reservation')->group(
    function () {
        Route::post('/store', [CarController::class, 'storeReservation']);
        Route::get('/{id}', [CarController::class, 'showReservation']);
        Route::put('/{carId}', [CarController::class, 'destroyReservation']);
    }
);
