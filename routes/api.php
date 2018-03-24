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

//$api = app('Dingo\Api\Routing\Router');
//
//$api->version('v1', [
//    'namespace' => 'App\Http\Controllers\Api',
//    'middleware' => ['serializer:array', 'bindings']
//], function($api) {
//    $api->group([
//        'middleware' => 'api.throttle',
//        'limit' => config('api.rate_limits.sign.limit'),
//        'expires' => config('api.rate_limits.sign.expires')
//    ], function($api) {
//        // 需要 token 验证的接口
//        $api->group(['middleware' => 'api.auth'], function($api) {
//            $api->post('companies', 'CompaniesController@store')
//                ->name('api.companies.store');
//            $api->get('search/{field}/like', 'CompaniesController@searchWithLike')
//                ->name('api.companies.search');
//        });
//    });
//});

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
Route::patch('user/homepages', 'Api\UsersController@updateHomepage');
Route::patch('user/role', 'Api\UsersController@updateRole');

Route::get('me/education_info', 'Api\EducationInfoController@currentUserFirstEducation');
Route::get('me/education_infos', 'Api\EducationInfoController@currentUserAllEducation');
Route::post('education_infos', 'Api\EducationInfoController@store');
Route::put('education_infos/{id}', 'Api\EducationInfoController@update');

Route::get('positionSkill', 'Api\PositionSkillController@index');

Route::get('me/work_experience', 'Api\WorkExperienceController@currentUserFirstWorkExperience');
Route::get('me/work_experiences', 'Api\WorkExperienceController@currentUserAllWorkExperience');
Route::post('work_experiences', 'Api\WorkExperienceController@store');
Route::put('work_experiences/{id}', 'Api\WorkExperienceController@update');

Route::get('industry', 'Api\IndustryController@index');

Route::post('expect_positions', 'Api\ExpectPositionController@store');

Route::post('project_experiences', 'Api\ProjectExperiencesController@store');
Route::put('project_experiences/{id}', 'Api\ProjectExperiencesController@update');
Route::delete('project_experiences/{projectExperience}', 'Api\ProjectExperiencesController@destroy');
Route::get('me/project_experiences', 'Api\ProjectExperiencesController@currentUserAllProjectExperience');

Route::post('companies', 'Api\CompaniesController@store')
    ->name('api.companies.store');
Route::get('companies/search/{field}/like', 'Api\CompaniesController@searchWithLike')
    ->name('api.companies.searchWithLike');
Route::get('companies/search', 'Api\CompaniesController@search')
    ->name('api.companies.search');
Route::get('companies/{company}/user', 'Api\CompaniesController@user')
    ->name('api.companies.user.store');

