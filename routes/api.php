<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UserController;
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


Route::middleware('auth:api')->get('user', [UserController::class, 'getUser']);

Route::middleware('auth:api')->post('updateuserimage', [UserController::class, 'updateUserImage']);

//    Route::get('/tasks', [TasksController::class, 'getTasks']);
//    Route::post('/tasks', [TasksController::class, 'create']);


    Route::post('/signin', [AuthController::class, 'signin']);
    Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);
    Route::post('/signup', [AuthController::class, 'signup']);





