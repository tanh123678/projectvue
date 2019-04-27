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
//register route
Route::post('auth/register', 'AuthController@register');
//login route
Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function(){
  Route::get('auth/user', 'AuthController@user');
  //route logout
  Route::post('auth/logout', 'AuthController@logout');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});
//send mail
Route::group([    
    'namespace' => 'Auth',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'ResetPasswordController@create');
    Route::get('find/{token}', 'ResetPasswordController@find');
    Route::post('reset', 'ResetPasswordController@reset');
});
//crud role
Route::group(['prefix' => '/role', 'namespace' => 'Crud\Role', 'as' => 'api.'], function () {
    Route::resource('roles', 'RolesController', ['except' => ['create', 'edit']]);
});
//crud user
Route::group(['prefix' => '/user', 'namespace' => 'Crud\User', 'as' => 'api.'], function () {
    Route::resource('users', 'UserCrudController', ['except' => ['create', 'edit']]);
});
//crud permission
Route::group(['prefix' => '/permission', 'namespace' => 'Crud\Permission', 'as' => 'api.'], function () {
    Route::resource('permissions', 'PermissionsCrudController', ['except' => ['create', 'edit']]);
});