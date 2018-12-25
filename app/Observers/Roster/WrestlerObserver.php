<?php

namespace App\Observers\Roster;

use App\Models\Roster\Wrestler;

class WrestlerObserver
{
    /**
     * Handle the Wrestler "creating" event.
     *
     * @param  \App\Models\Roster\Wrestler  $wrestler
     * @return void
     */
    public function creating(Wrestler $wrestler)
    {
        if (request()->has('feet') && request()->has('inches')) {
            $wrestler->height = request('feet') * 12 + request('inches');
        }
    }

    /**
     * Handle the Wrestler "updating" event.
     *
     * @param  \App\Models\Roster\Wrestler  $wrestler
     * @return void
     */
    public function updating(Wrestler $wrestler)
    {
        if (request()->has('feet') && request()->has('inches')) {
            $wrestler->height = request('feet') * 12 + request('inches');
        }
    }
}
