<?php

namespace App\Http\Controllers\Events;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Services\UpdateMatchResults;
use App\Http\Requests\EventResultsFormRequest;

class ResultsController extends Controller
{
    /** @var string */
    protected $authorizeResource = Event::class;

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
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

        return redirect()->route('events.show', ['event' => $event->id]);
    }
}
