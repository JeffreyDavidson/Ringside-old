<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasMatches
{
    abstract public function matches();

    /**
     * Retrieves the date of the model's first match.
     *
     * @return string
     */
    public function firstMatchDate()
    {
        return $this->pastMatches->first()->date;
    }

    /**
     * Returns the model's past matches.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pastMatches()
    {
        return $this->matches()->whereHas('event', function ($query) {
            $query->where('date', '<', Carbon::today());
        });
    }

    /**
     * Checks to see if the model has past matches.
     *
     * @return bool
     */
    public function hasPastMatches()
    {
        return $this->pastMatches->isNotEmpty();
    }

    /**
     * Returns the model's past matches.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function scheduledMatches()
    {
        return $this->matches()->whereHas('event', function ($query) {
            $query->where('date', '>=', Carbon::today());
        });
    }
}
