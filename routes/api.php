<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/signup', 'App\Http\Controllers\Api\AuthController@signup');
Route::post('/login', 'App\Http\Controllers\Api\AuthController@login');
Route::post('/forgot', 'App\Http\Controllers\Api\AuthController@forgot');
Route::post('/password/reset', 'App\Http\Controllers\Api\AuthController@reset');
Route::get('/getUserById/{id}', 'App\Http\Controllers\Api\AuthController@getUserById')->middleware('auth:sanctum');

