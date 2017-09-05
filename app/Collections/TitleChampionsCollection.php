<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class TitleChampionsCollection extends Collection
{
    public function groupByTitle()
    {
        return $this->groupBy('title_id');
    }
}