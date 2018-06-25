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

Route::get('login', function () {
    \Auth::loginUsingId(1);

    return redirect(route('dashboard'));
})->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'roster'], function () {
        Route::resource('wrestlers', 'WrestlersController');
        Route::get('wrestlers/{wrestler}/retire', 'WrestlersController@retire')->name('wrestlers.retire');
    });

    Route::resource('events', 'EventsController');
    Route::patch('events/{event}/archive', 'EventsController@archive')->name('events.archive');
    Route::get('events/{event}/results', 'ResultsController@edit')->name('results.edit');
    Route::patch('events/{event}/results', 'ResultsController@update')->name('results.update');
    Route::get('events/{event}/matches/create', 'MatchesController@create')->name('matches.create');
    Route::post('events/{event}/matches', 'MatchesController@store')->name('matches.store');
    Route::resource('titles', 'TitlesController');
    Route::get('titles/{title}/retire', 'TitlesController@retire')->name('titles.retire');
    Route::resource('stipulations', 'StipulationsController');
    Route::resource('venues', 'VenuesController');
});
