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
        $match->match_number = Match::forEvent($match->event)->count() + 1;
    }
}
