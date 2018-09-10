<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Match;
use App\Services\AddMatchesToEvent;
use App\Http\Requests\MatchCreateFormRequest;

class MatchesController extends Controller
{
    protected $authorizeResource = Match::class;

    /**
     * Show the form for creating a match for an event.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\Match  $match
     * @return \Illuminate\View\View
     */
    public function create(Event $event, Match $match)
    {
        return view('matches.create', compact('match'));
    }

    /**
     * Store newly created matches for an event.
     *
     * @param  \App\Http\Requests\MatchCreateFormRequest  $event
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MatchCreateFormRequest $request, Event $event)
    {
        (new AddMatchesToEvent($request->matches, $event))->schedule();

        return redirect()->route('events.show', ['event' => $event->id]);
    }

    /**
     * Display the specified match for an event.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\Match  $match
     * @return \Illuminate\View\View
     */
    public function show(Event $event, Match $match)
    {
        return view('matches.show', compact('match'));
    }

    /**
     * Show the form for editing a match for an event.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\Match  $match
     * @return \Illuminate\View\View
     */
    public function edit(Event $event, Match $match)
    {
        return view('matches.edit', compact('match'));
    }

    /**
     * Delete the specified match for an event.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event, Match $match)
    {
        $match->delete();

        return redirect()->route('event.matches.index', ['event' => $event->id]);
    }
}
