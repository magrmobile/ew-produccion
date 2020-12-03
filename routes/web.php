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

    // Charts
    Route::get('/charts/appointments/line', 'ChartsController@appointments');
});
