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

Route::post('/getCompanyByServiceId', 'App\Http\Controllers\Api\ActivityController@getCompanyByServiceId')->middleware('auth:sanctum');

// Route::post('/addEmployee', 'App\Http\Controllers\Api\ActivityController@addEmployee')->middleware('auth:sanctum');
// Route::post('/updateEmployee', 'App\Http\Controllers\Api\ActivityController@updateEmployee')->middleware('auth:sanctum');
// Route::post('/deleteEmployee', 'App\Http\Controllers\Api\ActivityController@deleteEmployee')->middleware('auth:sanctum');
// Route::post('/getEmployeeByCompanyId', 'App\Http\Controllers\Api\ActivityController@getEmployeeByCompanyId')->middleware('auth:sanctum');

Route::post('/addPortfolio', 'App\Http\Controllers\Api\ActivityController@addPortfolio')->middleware('auth:sanctum');
Route::post('/updatePortfolio', 'App\Http\Controllers\Api\ActivityController@updatePortfolio')->middleware('auth:sanctum');
Route::post('/deletePortfolio', 'App\Http\Controllers\Api\ActivityController@deletePortfolio')->middleware('auth:sanctum');

Route::post('/getPortfolioByCompany', 'App\Http\Controllers\Api\ActivityController@getPortfolioByCompany')->middleware('auth:sanctum');

Route::post('/addBankDetail', 'App\Http\Controllers\Api\ActivityController@addBankDetail')->middleware('auth:sanctum');
Route::post('/updateBankDetail', 'App\Http\Controllers\Api\ActivityController@updateBankDetail')->middleware('auth:sanctum');
Route::post('/deleteBankDetail', 'App\Http\Controllers\Api\ActivityController@deleteBankDetail')->middleware('auth:sanctum');
Route::post('/getBankDetail', 'App\Http\Controllers\Api\ActivityController@getBankDetail')->middleware('auth:sanctum');

Route::post('/likesCompany', 'App\Http\Controllers\Api\ActivityController@likesCompany')->middleware('auth:sanctum');
Route::post('/dislikesCompany', 'App\Http\Controllers\Api\ActivityController@dislikesCompany')->middleware('auth:sanctum');
Route::post('/unlikeCompany', 'App\Http\Controllers\Api\ActivityController@unlikeCompany')->middleware('auth:sanctum');
Route::post('/removeDislikeCompany', 'App\Http\Controllers\Api\ActivityController@removeDislikeCompany')->middleware('auth:sanctum');

Route::post('/addCompanyAd', 'App\Http\Controllers\Api\ActivityController@addCompanyAd')->middleware('auth:sanctum');
Route::post('/updateCompanyAd', 'App\Http\Controllers\Api\ActivityController@updateCompanyAd')->middleware('auth:sanctum');
Route::post('/deleteCompanyAd', 'App\Http\Controllers\Api\ActivityController@deleteCompanyAd')->middleware('auth:sanctum');
Route::post('/getCompanyAd', 'App\Http\Controllers\Api\ActivityController@getCompanyAd')->middleware('auth:sanctum');
Route::post('/addSubAd', 'App\Http\Controllers\Api\ActivityController@addSubAd')->middleware('auth:sanctum');
Route::post('/updateSubAd', 'App\Http\Controllers\Api\ActivityController@updateSubAd')->middleware('auth:sanctum');
Route::post('/deleteCompanySubAd', 'App\Http\Controllers\Api\ActivityController@deleteCompanySubAd')->middleware('auth:sanctum');

Route::post('/getCompanySubAdByCompanyAd', 'App\Http\Controllers\Api\ActivityController@getCompanySubAdByCompanyAd')->middleware('auth:sanctum');

Route::post('/CompanyAdReview', 'App\Http\Controllers\Api\ActivityController@CompanyAdReview')->middleware('auth:sanctum');
Route::post('/CompanySubAdReview', 'App\Http\Controllers\Api\ActivityController@CompanySubAdReview')->middleware('auth:sanctum');

Route::post('/getCompanyAdDetail', 'App\Http\Controllers\Api\ActivityController@getCompanyAdDetail')->middleware('auth:sanctum');
Route::post('/quoteMail', 'App\Http\Controllers\Api\ActivityController@quoteMail')->middleware('auth:sanctum');

Route::post('/getCompanyDetail', 'App\Http\Controllers\Api\ActivityController@getCompanyDetail')->middleware('auth:sanctum');
Route::post('/addEvent', 'App\Http\Controllers\Api\ActivityController@addEvent')->middleware('auth:sanctum');
Route::post('/updateEvent', 'App\Http\Controllers\Api\ActivityController@updateEvent')->middleware('auth:sanctum');
Route::post('/deleteEvent', 'App\Http\Controllers\Api\ActivityController@deleteEvent')->middleware('auth:sanctum');
Route::post('/getAllEvents', 'App\Http\Controllers\Api\ActivityController@getAllEvents')->middleware('auth:sanctum');

Route::post('/getEventDetail', 'App\Http\Controllers\Api\ActivityController@getEventDetail')->middleware('auth:sanctum');

Route::post('/addReviewEvent', 'App\Http\Controllers\Api\ActivityController@addReviewEvent')->middleware('auth:sanctum');
Route::post('/addGoingEvent', 'App\Http\Controllers\Api\ActivityController@addGoingEvent')->middleware('auth:sanctum');
Route::post('/unGoingEvent', 'App\Http\Controllers\Api\ActivityController@unGoingEvent')->middleware('auth:sanctum');
Route::post('/addInterestEvent', 'App\Http\Controllers\Api\ActivityController@addInterestEvent')->middleware('auth:sanctum');
Route::post('/unInterestEvent', 'App\Http\Controllers\Api\ActivityController@unInterestEvent')->middleware('auth:sanctum');
Route::get('/getAllMyEvents', 'App\Http\Controllers\Api\ActivityController@getAllMyEvents')->middleware('auth:sanctum');

Route::post('/bookingMail', 'App\Http\Controllers\Api\GuestController@bookingMail')->middleware('auth:sanctum');
Route::post('/dateSearchEvent', 'App\Http\Controllers\Api\GuestController@dateSearchEvent')->middleware('auth:sanctum');
Route::post('/getAllCategories', 'App\Http\Controllers\Api\ProfileController@getAllCategories')->middleware('auth:sanctum');
Route::post('/getPortfolioById', 'App\Http\Controllers\Api\ActivityController@getPortfolioById')->middleware('auth:sanctum');


Route::post('/addEmployee', 'App\Http\Controllers\Api\BusinessController@addEmployee')->middleware('auth:sanctum');
Route::post('/removeEmployee', 'App\Http\Controllers\Api\BusinessController@removeEmployee')->middleware('auth:sanctum');
Route::post('/getEmployeeByCompany', 'App\Http\Controllers\Api\BusinessController@getEmployeeByCompany')->middleware('auth:sanctum');

Route::post('/addPortfolioAd', 'App\Http\Controllers\Api\BusinessController@addPortfolioAd')->middleware('auth:sanctum');
Route::post('/updatePortfolioAd', 'App\Http\Controllers\Api\BusinessController@updatePortfolioAd')->middleware('auth:sanctum');
Route::post('/deletePortfolioAd', 'App\Http\Controllers\Api\BusinessController@deletePortfolioAd')->middleware('auth:sanctum');
Route::post('/getPortfolioAdByPortfolio', 'App\Http\Controllers\Api\BusinessController@getPortfolioAdByPortfolio')->middleware('auth:sanctum');
Route::get('/getAllSponserCompanyAd', 'App\Http\Controllers\Api\ActivityController@getAllSponserCompanyAd')->middleware('auth:sanctum');