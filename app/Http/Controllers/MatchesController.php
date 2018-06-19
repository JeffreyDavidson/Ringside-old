<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;
use App\Models\Event;
use App\Http\Requests\MatchCreateFormRequest;

class MatchesController extends Controller
{
    /**
     * Show the form for creating a match for an event.
     *
     * @param  Event $event
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
     * @param  Event $event
     * @param  MatchCreateFormRequest $event
     * @return \Illuminate\Http\Response
     */
    public function store(Event $event, MatchCreateFormRequest $request)
    {;
        foreach ($request->matches as $match) {
            $matchObj = $event->matches()->create([
                'match_number' => $match['match_number'],
                'match_type_id' => $match['match_type_id'],
                'stipulation_id' => $match['stipulation_id'],
                'preview' => $match['preview']
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
}
