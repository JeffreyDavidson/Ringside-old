<?php

namespace App\Observers;

use App\Models\Title;
use Carbon\Carbon;

class TitleObserver
{
    public function creating(Title $title)
    {
        $title->is_active = $title->introduced_at->lte(Carbon::today());
    }
}
