<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Match;
use App\Http\Requests\MatchCreateFormRequest;

class MatchesController extends Controller
{
    public function index(Event $event)
    {

    }

    /**
     * Show the form for creating a match for an event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event)
    {
        $this->authorize('create', Match::class);

        return response()->view('matches.create', ['match' => new Match]);
    }

    /**
     * Store a newly created matches.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Http\Requests\MatchCreateFormRequest  $event
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event, MatchCreateFormRequest $request)
    {
        foreach ($request->matches as $match) {
            $matchObj = $event->matches()->create([
                'match_type_id' => $match['match_type_id'],
                'stipulation_id' => $match['stipulation_id'],
                'preview' => $match['preview'],
            ]);

            if (! empty($match['titles'])) {
                foreach ($match['titles'] as $titleId) {
                    $matchObj->titles()->attach($titleId);
                }
            }

            if (! empty($match['referees'])) {
                foreach ($match['referees'] as $refereeId) {
                    $matchObj->referees()->attach($refereeId);
                }
            }

            if (! empty($match['wrestlers'])) {
                foreach ($match['wrestlers'] as $groupingId => $wrestlersArray) {
                    foreach ($wrestlersArray as $wrestlerId) {
                        $matchObj->wrestlers()->attach($wrestlerId, ['side_number' => $groupingId]);
                    }
                }
            }
        }

        return redirect()->route('events.show', ['event' => $event->id]);
    }

    /**
     * Display the specified match.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event, Match $match)
    {
        $this->authorize('show', Match::class);

        return response()->view('matches.show', ['match' => $match]);
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
     * Delete the specified match.
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
