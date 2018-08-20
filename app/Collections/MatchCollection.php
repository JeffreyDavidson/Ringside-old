<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class MatchCollection extends Collection
{
    /**
     * Groups a championship collection by Title.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function groupByTitle()
    {
        return $this->groupBy('title_id');
    }
}
