<?php

namespace App\Observers;

use App\Models\Match;

class MatchObserver
{
    public function creating(Match $match)
    {
        $match->match_number = Match::where('event_id', $match->event_id)->count() + 1;
    }
}
