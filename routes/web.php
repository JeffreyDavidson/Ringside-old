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

    Route::group(['prefix' => 'roster', 'namespace' => 'Roster'], function () {
        Route::group(['prefix' => 'wrestlers', 'namespace' => 'Wrestlers'], function () {
            Route::resource('/', 'WrestlersController')
                ->except('index')
                ->parameters(['' => 'wrestler'])
                ->names(['create' => 'wrestlers.create', 'edit' => 'wrestlers.edit', 'update' => 'wrestlers.update', 'show' => 'wrestlers.show', 'destroy' => 'wrestlers.destroy']);
            Route::get('active', 'ActiveWrestlersController@index')->name('active-wrestlers.index');
            Route::delete('active/{wrestler}/deactivate', 'ActiveWrestlersController@destroy')->name('active-wrestlers.deactivate');
            Route::get('inactive', 'InactiveWrestlersController@index')->name('inactive-wrestlers.index');
            Route::post('inactive/{wrestler}/activate', 'ActiveWrestlersController@store')->name('inactive-wrestlers.activate');
            Route::post('{wrestler}/retire', 'RetiredWrestlersController@store')->name('wrestlers.retire');
            Route::delete('retired/{wrestler}/unretire', 'RetiredWrestlersController@destroy')->name('retired-wrestlers.unretire');
        });
    });

    Route::group(['prefix' => 'events', 'namespace' => 'Events'], function () {
        Route::resource('/', 'EventsController')
            ->except('index')
            ->parameters(['' => 'event'])
            ->names(['create' => 'events.create', 'store' => 'events.store', 'edit' => 'events.edit', 'update' => 'events.update', 'show' => 'events.show', 'destroy' => 'events.destroy']);
        Route::get('scheduled', 'ScheduledEventsController@index')->name('scheduled-events.index');
        Route::get('past', 'PastEventsController@index')->name('past-events.index');
        Route::get('{event}/results', 'ResultsController@edit')->name('event-results.edit');
        Route::patch('{event}/results', 'ResultsController@update')->name('event-results.update');
        Route::get('archived', 'ArchivedEventsController@index')->name('archived-events.index');
        Route::post('{event}/archive', 'ArchivedEventsController@store')->name('past-events.archive');
        Route::delete('archived/{event}/unarchive', 'ArchivedEventsController@destroy')->name('archived-events.unarchive');
        Route::resource('.matches', 'MatchesController')
            ->parameters(['' => 'event'])
            ->names(['index' => 'matches.index', 'create' => 'matches.create']);
        Route::get('create', 'EventsController@create')->name('events.create');
    });

    Route::group(['prefix' => 'titles', 'namespace' => 'Titles'], function () {
        Route::resource('/', 'TitlesController')
            ->except('index')
            ->names(['create' => 'titles.create', 'store' => 'titles.store', 'show' => 'titles.show', 'edit' => 'titles.edit', 'update' => 'titles.update', 'destroy' => 'titles.destroy']);
        Route::get('active', 'ActiveTitlesController@index')->name('active-titles.index');
        Route::get('retired', 'RetiredTitlesController@index')->name('retired-titles.index');
        Route::post('{title}/reitre', 'RetiredTitlesController@store')->name('titles.retire');
        Route::delete('retired/{title}/unretire', 'RetiredTitlesController@destroy')->name('retired-titles.unretire');
    });

    Route::resource('stipulations', 'StipulationsController');
    Route::resource('venues', 'VenuesController');
});
