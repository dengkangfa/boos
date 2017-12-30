<?php

use Illuminate\Http\Request;

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
Route::post('login/{driver}', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::post('/user/avatar', 'Api\UsersController@avatar');
Route::put('/user/avatar', 'Api\UsersController@defaultAvatar');
Route::post('/user/crop/avatar', 'Api\UsersController@cropAvatar');
Route::patch('/user/profile', 'Api\UsersController@updateProfile');