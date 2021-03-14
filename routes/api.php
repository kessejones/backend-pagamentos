<?php

use App\Http\Controllers\Api\UserController;
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

Route::get('/user', [UserController::class, 'all']);
Route::post('/user', [UserController::class, 'create']);
Route::get('/user/{user}', [UserController::class, 'data']);
Route::post('/user/{user}/balance', [UserController::class, 'add_balance']);
