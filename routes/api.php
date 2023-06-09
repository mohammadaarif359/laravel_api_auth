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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
// auth
Route::post('/signup', 'AuthController@signup');
Route::post('/login', 'AuthController@login');

// after login route
Route::group(['middleware' => 'auth:api'], function () {
	Route::post('/is_logged', 'AuthController@isLogged');
	Route::get('/logout', 'AuthController@logout');
	// user
	Route::get('/user-profile', 'UserController@userProfile');
	Route::post('/user-update', 'UserController@userUpdate');
});	

