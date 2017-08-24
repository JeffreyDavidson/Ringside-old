<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class MatchCollection extends Collection
{
    public function eventDate()
    {
        return $this->event->date;
    }

    public function mainEventWrestlers()
    {
        return $this->wrestlers;
    }
}