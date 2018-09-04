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
        Route::get('retired-wrestlers', 'RetiredWrestlersController@index')->name('retired-wrestlers.index');
        Route::post('retired-wrestlers/{wrestler}', 'RetiredWrestlersController@store')->name('retired-wrestlers.store');
        Route::delete('retired-wrestlers/{wrestler}', 'RetiredWrestlersController@destroy')->name('retired-wrestlers.destroy');
    });

    Route::resource('events', 'EventsController');
    Route::get('events/{event}/results', 'ResultsController@edit')->name('results.edit');
    Route::patch('events/{event}/results', 'ResultsController@update')->name('results.update');
    Route::get('archived-events', 'ArchivedEventsController@index')->name('archived-events.index');
    Route::post('archived-events/{event}', 'ArchivedEventsController@store')->name('archived-events.store');
    Route::delete('archived-events/{event}', 'ArchivedEventsController@destroy')->name('archived-events.destroy');
    Route::resource('event.matches', 'MatchesController');
    Route::resource('titles', 'TitlesController');
    Route::get('retired-titles', 'RetiredTitlesController@index')->name('retired-titles.index');
    Route::post('retired-titles/{title}', 'RetiredTitlesController@store')->name('retired-titles.store');
    Route::delete('retired-titles/{title}', 'RetiredTitlesController@destroy')->name('retired-titles.destroy');
    Route::resource('stipulations', 'StipulationsController');
    Route::resource('venues', 'VenuesController');
});
