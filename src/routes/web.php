<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResponseController;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', [AuthController::class,'login'])->name('login');
    Route::post('register', [AuthController::class,'register']);
    Route::get('login', [ResponseController::class,'deny']);

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('logout', [AuthController::class,'logout']);
        Route::post('pixel', [AuthController::class,'pixelCreate']);

    });
});

