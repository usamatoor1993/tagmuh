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

Route::get('/getUserById/{id}', 'App\Http\Controllers\Api\AuthController@getUserById')->middleware('auth:sanctum');
Route::post('/reviewCompany', 'App\Http\Controllers\Api\GuestController@reviewCompany')->middleware('auth:sanctum');
Route::get('/getService', 'App\Http\Controllers\Api\GuestController@getService');

Route::post('/password/reset', 'App\Http\Controllers\Api\AuthController@reset');
Route::post('/updateProfile', 'App\Http\Controllers\Api\ProfileController@updateProfile')->middleware('auth:sanctum');
Route::post('/report', 'App\Http\Controllers\Api\ProfileController@report')->middleware('auth:sanctum');
Route::get('/getReports', 'App\Http\Controllers\Api\ProfileController@getReports')->middleware('auth:sanctum');
Route::post('/deleteUser', 'App\Http\Controllers\Api\GuestController@deleteUser')->middleware('auth:sanctum');
Route::get('/getVerifiedCompany', 'App\Http\Controllers\Api\ActivityController@getVerifiedCompany')->middleware('auth:sanctum');
Route::get('/getCompanies', 'App\Http\Controllers\Api\ActivityController@getCompanies')->middleware('auth:sanctum');
Route::post('/likes', 'App\Http\Controllers\Api\ActivityController@likes')->middleware('auth:sanctum');
Route::post('/dislikes', 'App\Http\Controllers\Api\ActivityController@dislikes')->middleware('auth:sanctum');
Route::post('/unlike', 'App\Http\Controllers\Api\ActivityController@unlike')->middleware('auth:sanctum');
Route::post('/removeDislike', 'App\Http\Controllers\Api\ActivityController@removeDislike')->middleware('auth:sanctum');
Route::post('/addCompany', 'App\Http\Controllers\Api\ActivityController@addCompany')->middleware('auth:sanctum');
Route::post('/updateCompany', 'App\Http\Controllers\Api\ActivityController@updateCompany')->middleware('auth:sanctum');
Route::post('/deleteCompany', 'App\Http\Controllers\Api\ActivityController@deleteCompany')->middleware('auth:sanctum');


