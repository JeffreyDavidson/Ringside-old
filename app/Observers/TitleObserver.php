<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Title;

class TitleObserver
{
    /**
     * If the title is introduced before today make the title active.
     *
     * @param  \App\Models\Title  $title
     * @return void
     */
    public function creating(Title $title)
    {
        $title->is_active = $title->introduced_at->lte(Carbon::today());
    }
}
