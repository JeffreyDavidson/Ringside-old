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

Route::get('login', function () {
    \Auth::loginUsingId(1);

    return redirect(route('dashboard'));
})->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'roster'], function () {
        Route::resource('wrestlers', 'WrestlersController');
    });
    Route::resource('events', 'EventsController');
    Route::resource('titles', 'TitlesController');
    Route::resource('stipulations', 'StipulationsController');
    Route::resource('venues', 'VenuesController');
});
