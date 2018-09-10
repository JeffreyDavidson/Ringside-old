<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class MatchCollection extends Collection
{
    public function groupWrestlersBySide()
    {
        return $this->groupBy('pivot.side_number');
    }
}
