<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class MatchCollection extends Collection
{
    public function matchDate()
    {
        return $this->first()->event->date;
    }
}