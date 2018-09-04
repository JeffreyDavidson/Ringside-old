<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\RosterMember;

class RosterMemberObserver
{
    /**
     * If the wrestler is hired before today make the wrestler active.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return void
     */
    public function creating(RosterMember $member)
    {
        $member->is_active = $member->hired_at->lte(Carbon::today());
    }
}
