<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class MatchCollection extends Collection
{
    /**
     * Groups each match wrestlers collection by the matching side number.
     *
     * @return static(App\Collections\MatchCollection)
     */
    public function groupedWrestlersBySide()
    {
        return $this->groupBy('pivot.side_number');
    }
}
