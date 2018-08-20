<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Match;
use App\Http\Requests\EventResultsFormRequest;
use App\Services\UpdateMatchResults;

class ResultsController extends Controller
{
    /**
     * Show the form for editing results for an event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $this->authorize('editResults', Event::class);

        return view('events.results', ['event' => $event]);
    }

    /**
     * Update the results of matches for an event.
     *
     * @param  \App\Http\Requests\EventResultsFormRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventResultsFormRequest $request, Event $event)
    {
        (new UpdateMatchResults($request->matches, $event))->save();

        return redirect()->route('events.index');
    }
}
