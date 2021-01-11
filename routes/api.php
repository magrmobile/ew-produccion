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
Route::get('/machines', 'MachineController@index');
Route::get('/machines/{machine}', 'MachineController@show');

// Product API
Route::get('/products', 'ProductController@index');
Route::get('/products/{product}', 'ProductController@show');

// Color API
Route::get('/colors', 'ColorController@index');
Route::get('/colors/{color}', 'ColorController@show');

// Code API
Route::get('/codes', 'CodeController@index');
Route::get('/codes/{code}', 'CodeController@show');

Route::group(['middleware' => ['auth:api']], function () {
    
    Route::get('/user', 'UserController@show');
    Route::post('/logout', 'AuthController@logout');

    // Post Appointment
    Route::get('/stops', 'StopController@index');
    Route::post('/stops', 'StopController@store');
});