<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\EventResultsFormRequest;
use App\Services\UpdateMatchResults;

class ResultsController extends Controller
{
    protected $authorizeResource = Event::class;

    protected function resourceAbilityMap()
    {
        return [
            'edit' => 'update-results',
            'update' => 'update-results',
        ];
    }

    /**
     * Show the form for editing results for an event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\View\View
     */
    public function edit(Event $event)
    {
        return view('events.results', compact('event'));
    }

    /**
     * Update the results of matches for an event.
     *
     * @param  \App\Http\Requests\EventResultsFormRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EventResultsFormRequest $request, Event $event)
    {
        (new UpdateMatchResults($request->matches, $event))->save();

        return redirect()->route('events.index');
    }
}
