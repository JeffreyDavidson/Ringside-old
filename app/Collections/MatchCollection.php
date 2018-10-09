<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class MatchCollection extends Collection
{
    /**
     * Groups each match wrestlers collection by the matching side number.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function groupWrestlersBySide()
    {
        return $this->groupBy('pivot.side_number');
    }
}
