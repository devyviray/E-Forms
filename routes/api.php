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

// Route::middleware(["auth"])->group(function () {
//     Route::get('getusers', 'UserController@getUsers');
// });

// // User routes
// Route::get('users', 'UserController@index');
// Route::get('user/{id}', 'UserController@show');
// Route::post('user', 'UserController@store');
// Route::patch('user/{user}', 'UserController@update');
// Route::delete('user/{id}', 'UserController@destroy');

// // Company routes
// Route::get('companies', 'CompanyController@index');
// Route::get('company/{id}', 'CompanyController@show');
// Route::post('company', 'CompanyController@store');
// Route::patch('company/{company}', 'UserController@update');
// Route::delete('company/{id}', 'CompanyController@destroy');

// // Department routes
// Route::get('departments', 'DepartmentController@index');
// Route::get('department/{id}', 'DepartmentController@show');
// Route::post('department', 'DepartmentController@store');
// Route::patch('department/{department}', 'DepartmentController@update');
// Route::delete('department/{id}', 'DepartmentController@destroy');


// // Role routes
// Route::get('roles', 'RoleController@index');
// Route::get('role/{id}', 'RoleController@show');
// Route::post('role', 'RoleController@store');
// Route::patch('role/{role}', 'RoleController@update');
// Route::delete('role/{id}', 'RoleController@destroy');

// // Permission routes
// Route::get('permissions', 'PermissionController@index');
// Route::get('permission/{id}', 'PermissionController@show');
// Route::post('permission', 'PermissionController@store');
// Route::patch('permission/{permission}', 'PermissionController@update');
// Route::delete('permission/{id}', 'PermissionController@destroy');


// Route::group(["middleware" => "auth"], function (){
//     Route::get('getusers', 'UserController@getUsers');
// });