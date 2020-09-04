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

Route::post('/register', 'Api\registerController@register');
Route::post('/login', 'Api\loginController@login');
Route::get('/publicPosts', 'Api\DashboardController@publicPost');

Route::group(['middleware'=>'auth:api'],function(){
    Route::post('/logout', 'Api\loginController@logout')->middleware('auth:api');
    Route::get('/dashboard', 'Api\DashboardController@index')->middleware('auth:api');
    Route::get('/dashboard/getInfo/{id}', 'Api\DashboardController@show')->middleware('auth:api');
    Route::delete('/dashboard/delete/{id}', 'Api\DashboardController@destroy')->middleware('auth:api');
    Route::post('/dashboard/create', 'Api\DashboardController@create')->middleware('auth:api');
});


Route::get('/dashboard/getPublicInfo/{id}', 'Api\DashboardController@showPublic');
Route::get('/shared/{id}', 'Api\DashboardController@showPublic');
Route::post('/dashboard/createPublic', 'Api\DashboardController@createPublic');
Route::post('/search', 'Api\DashboardController@searchIndex');
