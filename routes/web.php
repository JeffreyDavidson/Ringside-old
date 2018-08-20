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
    Route::view('/', 'dashboard')->name('dashboard');

    Route::group(['prefix' => 'roster'], function () {
        Route::resource('wrestlers', 'WrestlersController');
        Route::resource('retired-wrestlers', 'RetiredWrestlersController')->parameters([
            'retired-wrestlers' => 'wrestler',
        ]);
    });

    Route::resource('events', 'EventsController');
    Route::get('events/{event}/results', 'ResultsController@edit')->name('results.edit');
    Route::patch('events/{event}/results', 'ResultsController@update')->name('results.update');
    Route::resource('archived-events', 'ArchivedEventsController')->parameters([
        'archived-events' => 'event',
    ]);
    Route::resource('event.matches', 'MatchesController');
    Route::resource('titles', 'TitlesController');
    Route::resource('retired-titles', 'RetiredTitlesController')->parameters([
        'retired-titles' => 'title',
    ]);
    Route::resource('stipulations', 'StipulationsController');
    Route::resource('venues', 'VenuesController');
});
