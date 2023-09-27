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
    //Route::get('/charts/appointments/line', 'ChartsController@appointments');

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

    Route::get('stops-list-pdf','StopController@exportPdf')->name('stops.pdf');
    Route::get('stops-list-excel','StopController@exportExcel')->name('stops.excel');

    
    // Rounds Routes
    Route::resource('rounds', 'RoundController');
    //Route::get('/rounds/index', 'RoundController@index')->name('rounds.index');
    //Route::get('/rounds/{id}', 'RoundController@show')->name('rounds.show');
    //Route::get('/rounds/{id}/edit', 'RoundController@edit')->name('rounds.edit');
    //Route::put('/rounds/{id}', 'RoundController@update')->name('rounds.update');
    //Route::delete('/rounds/{id}', 'RoundController@destroy')->name('rounds.destroy');
    Route::get('/dashboard', 'RoundController@dashboard')->name('rounds.dashboard');
    Route::get('/get-machine-hours', 'RoundController@getMachineHours');
    //Route::get('/rounds/missing', 'RoundController@missingRounds');

    Route::get('/missing-rounds', 'RoundController@missingRounds')->name('rounds.missing');
    Route::get('/get-production-speed', 'RoundController@getProductionSpeed');
    Route::get('/get-lastround-product', 'RoundController@getLastRoundProduct');

    // Route::get('/rounds/create', 'RoundController@create');
    // Route::post('/rounds', 'RoundController@store');
   
    Route::get('/billing', 'BillingController@index');
    Route::post('/upload', 'BillingController@upload');
    Route::post('/get-customer-data', 'BillingController@getCustomerData');
    Route::get('/generar-schema','BillingController@generarJson');
    Route::get('/mostrar-pdf/{json}', 'BillingController@generarPDF');

    Route::get('/obtener-pdf', function(){
        try {
            // Obten el contenido del archivo PDF almacenado en app/temp.pdf
            $pdfFilePath = storage_path('app/'.session()->getId().'.pdf');

            // Crea una respuesta HTTP con el contenido del archivo PDF
            return response()->file($pdfFilePath, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (Exception $e){
            echo "Error: No ha generado aun ningun Documento Tributario Electronico";
        }
    })->name('obtener.pdf');

    Route::post('/guardar-dte', 'BillingController@guardarDTE')->name('guardar.dte');

    // Customer Routes
    Route::resource('customers', 'CustomerController');

    // Dte Routes
    Route::resource('dtes', 'dteController');
    Route::get('/signDte/{id}', 'dteController@signDte')->name('dtes.signDte');
});
