<?php

namespace App\Observers;

use App\Models\Match;

class MatchObserver
{
    public function creating(Match $match)
    {
        $match->match_number = Match::forEvent($match->event)->count() + 1;
    }
}
