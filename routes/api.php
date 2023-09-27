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
Route::get('/products/process/{process}', 'ProductController@getByProcess');

// Color API
Route::get('/colors', 'ColorController@index');
Route::get('/colors/{color}', 'ColorController@show');

Route::get('/colorst', 'ColorController@color_test');

// Code API
Route::get('/codes', 'CodeController@index');
Route::get('/codes/{code}', 'CodeController@show');

// Conversion API
Route::get('/conversions', 'ConversionController@index');
Route::get('/conversions/{conversion}', 'ConversionController@show');

Route::get('/stops_report','StopController@stops_report');

// Datatables
Route::get('/datatable/codes', 'DatatableController@code')->name('datatable.codes');
Route::get('/datatable/conversions', 'DatatableController@conversion')->name('datatable.conversions');
Route::get('/datatable/processes', 'DatatableController@process')->name('datatable.processes');
Route::get('/datatable/customers', 'DatatableController@customer')->name('datatable.customers');
Route::get('/datatable/devices', 'DatatableController@device')->name('datatable.devices');
Route::get('/datatable/families', 'DatatableController@family')->name('datatable.families');
Route::get('/datatable/machines', 'DatatableController@machine')->name('datatable.machines');
Route::get('/datatable/products', 'DatatableController@product')->name('datatable.products');
Route::get('/datatable/stops', 'DatatableController@stop')->name('datatable.stops');
Route::get('/datatable/operators', 'DatatableController@operator')->name('datatable.operators');
Route::get('/datatable/supervisors', 'DatatableController@supervisor')->name('datatable.supervisors');
Route::get('/datatable/dtes/{customer_id}', 'DatatableController@dte')->name('datatable.dtes');

Route::get('/users', 'UserController@index');
Route::get('/operators', 'UserController@operators');
Route::get('/user/process/{machine_id}', 'UserController@operatorsByProcess');
Route::get('/dev_machines', 'DeviceController@machines');

// Stops
Route::get('/stops', 'StopController@index');
Route::post('/stops', 'StopController@store');
Route::get('/stops/{stop}', 'StopController@show');
Route::put('/stops/{stop}', 'StopController@update');
Route::delete('/stops/{stop}', 'StopController@destroy');

Route::group(['middleware' => ['auth:api']], function () {
    
    Route::get('/user', 'UserController@show');
    Route::post('/logout', 'AuthController@logout');

    
    //Route::resource('stops', 'StopController');

    Route::get('/last_datetime_stop', 'StopController@last_datetime_stop');
});