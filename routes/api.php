<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

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

Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('login', [AuthController::class, 'loginUser']);
    Route::post('register', [AuthController::class, 'registerUser']);

    // Routes for profile
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [AuthController::class, 'logoutUser']);
        Route::get('profile', [UserController::class, 'profileUser']);
        Route::put('profile', [UserController::class, 'updateProfile']);
        Route::put('update-password', [UserController::class, 'updatePassword']);

        Route::delete('user/{id}', [UserController::class, 'deleteUser'])->middleware('admin');
        Route::post('user', [UserController::class, 'createUser'])->middleware('admin');
    });
});
