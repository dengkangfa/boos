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

//$api = app('Dingo\Routing\Router');
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

Route::namespace('Api')->group(function () {
    Route::get('user', 'UsersController@me');
    Route::post('user/avatar', 'UsersController@uploadAvatar');
    Route::get('user/avatar', 'UsersController@avatar');
    Route::patch('user/avatar', 'UsersController@defaultAvatar');
    Route::post('user/crop/avatar', 'UsersController@cropAvatar');
    Route::patch('user/profile', 'UsersController@updateProfile');
    Route::patch('user/advantage', 'UsersController@updateAdvantage');
    Route::patch('user/homepages', 'UsersController@updateHomepage');
    Route::patch('user/role', 'UsersController@updateRole');

    Route::get('me/education_info', 'EducationInfoController@currentUserFirstEducation');
    Route::get('me/education_infos', 'EducationInfoController@currentUserAllEducation');
    Route::post('education_infos', 'EducationInfoController@store');
    Route::put('education_infos/{id}', 'EducationInfoController@update');

    Route::get('positionSkill', 'PositionSkillController@index');

    Route::get('me/work_experience', 'WorkExperienceController@currentUserFirstWorkExperience');
    Route::get('me/work_experiences', 'WorkExperienceController@currentUserAllWorkExperience');
    Route::post('work_experiences', 'WorkExperienceController@store');
    Route::put('work_experiences/{id}', 'WorkExperienceController@update');

    Route::get('industry', 'IndustryController@index');

    Route::post('expect_positions', 'ExpectPositionController@store');

    Route::post('project_experiences', 'ProjectExperiencesController@store');
    Route::put('project_experiences/{id}', 'ProjectExperiencesController@update');
    Route::delete('project_experiences/{projectExperience}', 'ProjectExperiencesController@destroy');
    Route::get('me/project_experiences', 'ProjectExperiencesController@currentUserAllProjectExperience');

    Route::get('companies', 'CompaniesController@index')
        ->name('api.companies.index');
    Route::post('companies', 'CompaniesController@store')
        ->name('api.companies.store');
    Route::get('companies/search/{field}/like', 'CompaniesController@searchWithLike')
        ->name('api.companies.searchWithLike');
    Route::get('companies/search', 'CompaniesController@search')
        ->name('api.companies.search');
    Route::get('companies/{company}/user', 'CompaniesController@user')
        ->name('api.companies.user.store');
    // 发布工作
    Route::post('companies/{company}/jobs', 'JobController@store')
        ->name('api.companies.job.store');
    // 获取工作列表
    Route::get('jobs', 'JobController@index')
        ->name('api.jobs.index');
    Route::get('jobs/{job}', 'JobController@show')
        ->name('api.jobs.show');
});

