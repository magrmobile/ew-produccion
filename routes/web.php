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

// Admin
Route::middleware(['auth','admin'])->namespace('Admin')->group(function() {
    // Operators
    Route::resource('operators', 'OperatorController');

    // Supervisors
    Route::resource('supervisors', 'SupervisorController');

    // Machines
    Route::resource('machines', 'MachineController');

    // Products
    Route::resource('products', 'ProductController');

    // Codes
    Route::resource('codes', 'CodeController');

    // Colors
    Route::resource('colors', 'ColorController');

    // Charts
    Route::get('/charts/appointments/line', 'ChartsController@appointments');
});

Route::middleware('auth')->group(function(){
    Route::get('/stops/create', 'StopController@create');
    Route::post('/stops', 'StopController@store');

    /*
    /appointments -> Verificar
    -> que variables pasar a la vista
    -> 1 unico blade (condiciones)
    */
    Route::get('/stops', 'StopController@index');
    Route::get('/stops/{stop}', 'StopController@show');

    Route::get('/stops/{stop}/cancel', 'StopController@showCancelForm');
    Route::post('/stops/{stop}/cancel', 'StopController@postCancel');

    Route::post('/stops/{stop}/confirm', 'StopController@postConfirm');
});
