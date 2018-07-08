<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Wrestler;

class WrestlerObserver
{
    public function creating(Wrestler $wrestler)
    {
        $wrestler->is_active = $wrestler->hired_at->lte(Carbon::today());
    }
}
