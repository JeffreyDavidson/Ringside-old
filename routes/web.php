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
            Route::resource('active', 'ActiveWrestlersController')
                ->only(['index', 'destroy'])
                ->parameters(['active' => 'wrestler'])
                ->names(['index' => 'active-wrestlers.index', 'destroy' => 'active-wrestlers.deactivate']);
            Route::resource('inactive', 'InactiveWrestlersController')
                ->only(['index', 'destroy'])
                ->parameters(['inactive' => 'wrestler'])
                ->names(['index' => 'inactive-wrestlers.index', 'destroy' => 'inactive-wrestlers.activate']);
            Route::resource('retired', 'RetiredWrestlersController')
                ->only(['index', 'store', 'destroy'])
                ->parameters(['retired' => 'wrestler'])
                ->names(['index' => 'retired-wrestlers.index', 'store' => 'retired-wrestlers.store', 'destroy' => 'retired-wrestlers.destroy']);
            Route::get('/', 'WrestlersController@index')->name('wrestlers.index');
            Route::get('create', 'WrestlersController@create')->name('wrestlers.create');
            Route::get('{wrestler}', 'WrestlersController@show')->name('wrestlers.show');
            Route::post('/', 'WrestlersController@store')->name('wrestlers.store');
            Route::get('edit/{wrestler}', 'WrestlersController@edit')->name('wrestlers.edit');
            Route::patch('{wrestler}', 'WrestlersController@update')->name('wrestlers.update');
            Route::delete('{wrestler}', 'WrestlersController@destroy')->name('wrestlers.destroy');
        });
    });

    Route::group(['prefix' => 'events', 'namespace' => 'Events'], function () {
        Route::resource('/', 'EventsController');
        Route::resource('archived', 'ArchivedEventsController');
        Route::resource('scheduled', 'ScheduledEventsController');
        Route::get('{event}/results', 'ResultsController@edit')->name('results.edit');
        Route::patch('{event}/results', 'ResultsController@update')->name('results.update');
        // Route::get('archived-events', 'ArchivedEventsController@index')->name('archived-events.index');
        // Route::post('archived-events/{event}', 'ArchivedEventsController@store')->name('archived-events.store');
        // Route::delete('archived-events/{event}', 'ArchivedEventsController@destroy')->name('archived-events.destroy');
        Route::resource('matches', 'MatchesController');
        Route::get('create', 'EventsController@create')->name('events.create');
    });

    Route::group(['prefix' => 'titles', 'namespace' => 'Titles'], function () {
        Route::resource('titles', 'TitlesController');
        Route::get('retired-titles', 'RetiredTitlesController@index')->name('retired-titles.index');
        Route::post('retired-titles/{title}', 'RetiredTitlesController@store')->name('retired-titles.store');
        Route::delete('retired-titles/{title}', 'RetiredTitlesController@destroy')->name('retired-titles.destroy');
    });

    Route::resource('stipulations', 'StipulationsController');
    Route::resource('venues', 'VenuesController');
});
