<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ChampionshipCollection extends Collection
{
    /**
     * Groups a championship collection by the title.
     *
     * @return static(App\Collections\ChampionshipCollection)
     */
    public function groupByTitle()
    {
        return $this->groupBy('title_id');
    }
}
