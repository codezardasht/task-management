<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ListBoardController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RoleController;
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

Route::group([
    'prefix' => 'auth',
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});


Route::group([
    'middleware' => ['auth:api','throttle:api'],
], function ($router) {
    Route::apiResource('user', UserController::class);

    Route::get('board/{board}/status_board',[BoardController::class,'status_board']);
    Route::apiResource('board', BoardController::class);

    Route::apiResource('label', LabelController::class);
    Route::apiResource('listBoard', ListBoardController::class);

    Route::get('task/duy_date',[TaskController::class,'duy_date']);
    Route::patch('task/{task}/move',[TaskController::class,'movie']);
    Route::patch('task/{task}/assign',[TaskController::class,'assign']);
    Route::apiResource('task', TaskController::class);

    Route::apiResource('role', RoleController::class);
});
