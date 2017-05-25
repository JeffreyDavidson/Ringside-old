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
    return view('dashboard');
})->name('dashboard');

Route::get('login', function() {
    \Auth::loginUsingId(1);
    return redirect(route('dashboard'));
})->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::get('wrestlers/active', 'ActiveWrestlersController@index')->name('wrestlers.active');
    Route::get('wrestlers/inactive', 'InactiveWrestlersController@index')->name('wrestlers.inactive');
    Route::get('wrestlers/injured', 'InjuredWrestlersController@index')->name('wrestlers.injured');
    Route::get('wrestlers/suspended', 'SuspendedWrestlersController@index')->name('wrestlers.suspended');
    Route::get('wrestlers/retired', 'RetiredWrestlersController@index')->name('wrestlers.retired');
    Route::resource('wrestlers', 'WrestlersController');
    Route::resource('events', 'EventsController');
    Route::resource('titles', 'TitlesController');
    Route::resource('stipulations', 'StipulationsController');
    Route::resource('venues', 'VenuesController');
    Route::resource('wrestler-statuses', 'WrestlerStatusesController', ['only' => ['index']]);

});
