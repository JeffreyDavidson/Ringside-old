<?php

namespace App\Traits;

trait HasMatches
{
    abstract public function matches();

    /**
     * Retrieves date of the wrestler's first match.
     *
     * @return string
     */
    public function firstMatchDate()
    {
        return $this->pastMatches()->first()->date;
    }

    /**
     * Returns a collection of matches for the model before the current date.
     *
     */
    public function pastMatches()
    {
        return $this->matches->filter->isPast();
    }

    /**
     * Finds out if the model has been associated to a past match.
     *
     * @return boolean
     */
    public function hasPastMatches()
    {
        return $this->pastMatches()->isNotEmpty();
    }
}
