<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Match;
use App\Http\Requests\MatchCreateFormRequest;
use App\Services\AddMatchesToEvent;

class MatchesController extends Controller
{
    /**
     * Show the form for creating a match for an event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event, Match $match)
    {
        $this->authorize('create', Match::class);

        return view('matches.create', compact('match'));
    }

    /**
     * Store newly created matches for an event.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Http\Requests\MatchCreateFormRequest  $event
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event, MatchCreateFormRequest $request)
    {
        (new AddMatchesToEvent($request->matches, $event))->schedule();

        return redirect()->route('events.show', ['event' => $event->id]);
    }

    /**
     * Display the specified match for an event.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event, Match $match)
    {
        $this->authorize('show', Match::class);

        return view('matches.show', compact('match'));
    }

    /**
     * Show the form for editing a match for an event.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event, Match $match)
    {
        $this->authorize('edit', Match::class);
    }

    /**
     * Delete the specified match for an event.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event, Match $match)
    {
        $this->authorize('delete', Match::class);

        $match->delete();

        return redirect()->route('event.matches.index', ['event' => $event->id]);
    }
}
