<?php

namespace App\Observers\Roster;

use App\Models\Roster\TagTeam;

class TagTeamObserver
{
    /**
     * Set active state based off of when the title was introduced.
     *
     * @param  \App\Models\TagTeam  $tagteam
     * @return void
     */
    public function created(TagTeam $tagteam)
    {
        
    }
}
