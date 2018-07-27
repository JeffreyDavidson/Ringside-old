<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Wrestler;

class WrestlerObserver
{
    /**
     * If the wrestler is hired before today make the wrestler active.
     *
     * @param  \App\Models\Wrestler  $wrestler
     * @return void
     */
    public function creating(Wrestler $wrestler)
    {
        $wrestler->is_active = $wrestler->hired_at->lte(Carbon::today());
    }
}
