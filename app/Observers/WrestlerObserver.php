<?php

namespace App\Observers;

use App\Models\Wrestler;
use Carbon\Carbon;

class WrestlerObserver
{
    public function creating(Wrestler $wrestler)
    {
        $wrestler->is_active = $wrestler->hired_at->lte(Carbon::today());
    }
}
