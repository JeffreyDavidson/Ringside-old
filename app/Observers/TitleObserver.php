<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Title;

class TitleObserver
{
    public function creating(Title $title)
    {
        $title->is_active = $title->introduced_at->lte(Carbon::today());
    }
}
