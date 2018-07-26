<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class ChampionshipCollection extends Collection
{
    public function groupByTitle()
    {
        return $this->groupBy('title_id');
    }
}
