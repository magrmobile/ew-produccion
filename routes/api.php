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
Route::get('/codes', 'Api\CodeController@index');
Route::get('/codes/{code}', 'Api\CodeController@show');

Route::group(['middleware' => ['auth:api']], function () {
    
    Route::get('/user', 'UserController@show');
    Route::post('/logout', 'AuthController@logout');

    // Post Appointment
});