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

Route::post('login/{driver}', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
Route::post('token/refresh', 'Auth\LoginController@refresh');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('user', 'Api\UsersController@me');
Route::post('user/avatar', 'Api\UsersController@uploadAvatar');
Route::get('user/avatar', 'Api\UsersController@avatar');
Route::patch('user/avatar', 'Api\UsersController@defaultAvatar');
Route::post('user/crop/avatar', 'Api\UsersController@cropAvatar');
Route::patch('user/profile', 'Api\UsersController@updateProfile');
Route::patch('user/advantage', 'Api\UsersController@updateAdvantage');

Route::get('me/education_infos', 'Api\EducationInfoController@index');
Route::post('education_infos', 'Api\EducationInfoController@store');
Route::put('education_infos/{id}', 'Api\EducationInfoController@update');

Route::get('positionSkill', 'Api\PositionSkillController@index');