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

Route::post('/login', 'AuthController@login');

// Public Resources

// Machine API
Route::get('/machines', 'Api\MachineController@index');
Route::get('/machines/{machine}', 'Api\MachineController@show');

// Product API
Route::get('/products', 'Api\ProductController@index');
Route::get('/products/{product}', 'Api\ProductController@show');

// Color API
Route::get('/colors', 'Api\ColorController@index');
Route::get('/colors/{color}', 'Api\ColorController@show');

// Code API
Route::get('/codes', 'Api\CodeController@index');
Route::get('/codes/{code}', 'Api\CodeController@show');

Route::group(['middleware' => ['auth:api']], function () {
    
    Route::get('/user', 'UserController@show');
    Route::post('/logout', 'AuthController@logout');

    // Post Appointment
});