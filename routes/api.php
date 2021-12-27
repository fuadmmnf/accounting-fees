<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index']);
Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store']);
Route::get('/fields', [\App\Http\Controllers\FieldController::class, 'index']);


Route::post('/login', [\App\Http\Controllers\ApplicationController::class, 'login']);
Route::get('/applications', [\App\Http\Controllers\ApplicationController::class, 'index']);
Route::post('/applications', [\App\Http\Controllers\ApplicationController::class, 'store']);
Route::delete('/applications/{id}', [\App\Http\Controllers\ApplicationController::class, 'destroy']);
