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

Route::group(['prefix' => 'auth'], function () {

    //Login: /api/auth/login
    Route::post('login', 'AuthController@login');

    Route::middleware('auth:sanctum')->group( function () {

        //Logout: /api/auth/logout
        Route::post('logout', 'AuthController@logout');

        //Refresh token: /api/auth/refresh
        //Route::post('refresh', 'AuthController@refresh');

        Route::get('principal', 'AuthController@principal');

    });
});

//UserController
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'user'], function () {

    //Principal: /api/user/
    Route::get('/', 'UserController@index');

    //roles: /api/user/roles
    Route::get('roles', 'UserController@roles');

    //Principal: /api/user/hasRole
    Route::post('hasRole', 'UserController@hasRole');

    //Profil: ...

});

/*
//Evaluation
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'eval'], function ($router) {

    Route::get('test', 'UserController@testUser');
    Route::post('classes', 'UserController@getClasses');

    //Evaluation
    Route::post('beforeEvaluation', 'EvaluationController@getBeforeEvaluation');
    Route::post('dataEvaluation', 'EvaluationController@getDataEvaluation');


    Route::post('params', 'EvalController@params');
    //Datas
    Route::post('datas/index', 'EvalController@index');
    //Route::get('datas/create', 'EvalController@create');
    Route::post('datas/store', 'EvalController@store');
    Route::get('datas/{key}', 'EvalController@show');
    //Route::get('datas/{item}/edit', 'EvalController@edit'); //Unavailable
    Route::match(['patch', 'put'], 'datas/{item}', 'EvalController@update');
    Route::delete('datas/{item}', 'EvalController@destroy');
});
*/
