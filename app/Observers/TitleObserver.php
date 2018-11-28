<?php

namespace App\Observers;

use App\Models\Title;

class TitleObserver
{
    /**
     * Set active state based off of when the title was introduced.
     *
     * @param  \App\Models\Title  $title
     * @return void
     */
    public function creating(Title $title)
    {
        $title->is_active = $title->introduced_at->lte(today());
    }

    /**
     * Set active state based off of when the title was introduced.
     *
     * @param  \App\Models\Title  $title
     * @return void
     */
    public function saving(Title $title)
    {
        if ($title->isActive()) {
            $title->is_active = $title->introduced_at->lte(today());
        }
    }
}
