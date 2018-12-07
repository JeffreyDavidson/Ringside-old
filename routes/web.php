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
        Route::get('wrestlers/active', 'Wrestler\\ActiveWrestlersController@index')->name('active-wrestlers.index');
        Route::get('wrestlers/inactive', 'Wrestler\\InactiveWrestlersController@index')->name('inactive-wrestlers.index');
        Route::get('wrestlers/retired', 'Wrestler\\RetiredWrestlersController@index')->name('retired-wrestlers.index');
        Route::resource('wrestlers', 'Wrestler\\WrestlersController')->except('index')->parameter('wrestlers', 'wrestler');

        Route::group(['prefix' => 'wrestlers', 'namespace' => 'Wrestler'], function () {
            Route::delete('active/{wrestler}/deactivate', 'ActiveWrestlersController@destroy')->name('active-wrestlers.deactivate');
            Route::post('inactive/{wrestler}/activate', 'ActiveWrestlersController@store')->name('inactive-wrestlers.activate');
            Route::post('{wrestler}/retire', 'RetiredWrestlersController@store')->name('wrestlers.retire');
            Route::delete('retired/{wrestler}/unretire', 'RetiredWrestlersController@destroy')->name('retired-wrestlers.unretire');
        });

        Route::get('tagteams/active', 'TagTeam\\ActiveTagTeamsController@index')->name('active-tagteams.index');
        Route::get('tagteams/inactive', 'TagTeam\\InactiveTagTeamsController@index')->name('inactive-tagteams.index');
        Route::get('tagteams/retired', 'TagTeam\\RetiredTagTeamsController@index')->name('retired-tagteams.index');
        Route::resource('tagteams', 'TagTeam\\TagTeamsController')->except('index');
    });

    Route::get('events/scheduled', 'Events\\ScheduledEventsController@index')->name('scheduled-events.index');
    Route::get('events/past', 'Events\\PastEventsController@index')->name('past-events.index');
    Route::get('events/archived', 'Events\\ArchivedEventsController@index')->name('archived-events.index');
    Route::resource('events', 'Events\\EventsController')->except('index');

    Route::group(['prefix' => 'events', 'namespace' => 'Events'], function () {
        Route::get('{event}/results', 'ResultsController@edit')->name('event-results.edit');
        Route::patch('{event}/results', 'ResultsController@update')->name('event-results.update');
        Route::resource('.matches', 'MatchesController')
            ->parameters(['' => 'event'])
            ->names(['index' => 'matches.index', 'create' => 'matches.create']);
        Route::get('create', 'EventsController@create')->name('events.create');
        Route::post('{event}/archive', 'ArchivedEventsController@store')->name('archived-events.store');
        Route::delete('archived/{event}/unarchive', 'ArchivedEventsController@destroy')->name('archived-events.unarchive');
    });

    Route::get('titles/active', 'Titles\\ActiveTitlesController@index')->name('active-titles.index');
    Route::get('titles/inactive', 'Titles\\InactiveTitlesController@index')->name('inactive-titles.index');
    Route::get('titles/retired', 'Titles\\RetiredTitlesController@index')->name('retired-titles.index');
    Route::resource('titles', 'Titles\\TitlesController')->except('index');  

    Route::group(['prefix' => 'titles', 'namespace' => 'Titles'], function () {
        Route::delete('active/{title}/deactivate', 'ActiveTitlesController@destroy')->name('active-titles.deactivate');
        Route::post('inactive/{title}/activate', 'ActiveTitlesController@store')->name('inactive-titles.activate');
        Route::post('{title}/retire', 'RetiredTitlesController@store')->name('titles.retire');
        Route::delete('retired/{title}/unretire', 'RetiredTitlesController@destroy')->name('retired-titles.unretire');
    });

    Route::resource('stipulations', 'StipulationsController');
    Route::resource('venues', 'VenuesController');
});
