<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ChampionshipCollection extends Collection
{
    /**
     * Groups a championship collection by the title.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function groupByTitle()
    {
        return $this->groupBy('title_id');
    }
}
