<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login'); // view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/getmacexec', function() {
    $mac = gethostbyname(gethostname());
    dd($mac);
});

// Admin
Route::middleware(['auth','admin'])->namespace('Admin')->group(function() {
    // Operators
    Route::resource('operators', 'OperatorController');

    // Supervisors
    Route::resource('supervisors', 'SupervisorController');

    // Processes
    Route::resource('processes', 'ProcessController');

    // Machines
    Route::resource('devices', 'DeviceController');

    // Machines
    Route::resource('machines', 'MachineController');

    // Codes
    Route::resource('codes', 'CodeController');

    // Colors
    Route::resource('colors', 'ColorController');

    // Families
    Route::resource('families', 'FamilyController');

    // Conversions
    Route::resource('conversions', 'ConversionController');

    // Charts
    Route::get('/charts/appointments/line', 'ChartsController@appointments');
});

Route::middleware('auth')->group(function(){
    // Products
    Route::resource('products', 'Admin\ProductController');
    Route::get('/search', 'Select2SearchController@index');
    Route::get('/ajax-autocomplete-search', 'Select2SearchController@selectSearch');

    /*Route::get('/stops', 'StopController@index');
    Route::get('/stops/create', 'StopController@create');
    Route::get('/stops/{stop}', 'StopController@show');
    Route::get('/stops/{stop}/edit', 'StopController@edit');

    Route::post('/stops', 'StopController@store');
    Route::put('/stops/{stop}', 'StopController@update');*/

    Route::resource('stops', 'StopController');
});
