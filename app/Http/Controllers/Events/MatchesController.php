<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatchCreateFormRequest;
use App\Models\Event;
use App\Models\Match;

class MatchesController extends Controller
{
    /** @var string */
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
        // dd('testing');
        // (new AddMatchesToEvent($request->matches, $event))->schedule();

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

        return redirect()->route('matches.index', ['event' => $event->id]);
    }
}
