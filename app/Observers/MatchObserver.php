<?php

namespace App\Observers;

use App\Models\Match;

class MatchObserver
{
    /**
     * Calculate the match number for the match for the event.
     *
     * @param  \App\Models\Match  $match
     * @return void
     */
    public function creating(Match $match)
    {
        $match->match_number = Match::forEvent($match->event)->max('match_number') + 1;
    }

    /**
     * Reorders matches for an event when a match is deleted.
     *
     * @param  \App\Models\Match  $match
     * @return void
     */
    public function deleted(Match $match)
    {
        Match::forEvent($match->event_id)->where('match_number', '>', $match->match_number)->decrement('match_number');
    }
}
